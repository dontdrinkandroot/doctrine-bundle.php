<?php

namespace Dontdrinkandroot\DoctrineBundle\Service\TransactionManager;

use Doctrine\ORM\EntityManagerInterface;
use Exception;
use InvalidArgumentException;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
class TransactionManager
{
    private LoggerInterface $logger;

    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->logger = new NullLogger();
    }

    public function beginTransaction(): void
    {
        $this->entityManager->beginTransaction();
    }

    public function commitTransaction(bool $forceFlush = false): bool
    {
        $nestingLevel = $this->entityManager->getConnection()->getTransactionNestingLevel();

        /* No active transaction */
        if (!$this->isInTransaction()) {
            $this->logger->warning('No active Transaction for commit');

            return false;
        }

        /* Topmost transaction, flush */
        $flushed = false;
        if ($forceFlush || 1 === $nestingLevel) {
            $this->entityManager->flush();
            $flushed = true;
        }
        $this->entityManager->commit();

        return $flushed;
    }

    public function rollbackTransaction(bool $closeOnException = true): void
    {
        /* No active transaction */
        if (!$this->isInTransaction()) {
            $this->logger->warning('No active Transaction for commit');

            return;
        }

        if ($closeOnException) {
            $this->entityManager->close();
        }
        $this->entityManager->rollback();
    }

    public function isInTransaction(): bool
    {
        return 0 !== $this->entityManager->getConnection()->getTransactionNestingLevel();
    }

    public function transactional(callable $func, bool $forceFlush = false, bool $closeOnException = true)
    {
        if (!is_callable($func)) {
            throw new InvalidArgumentException('Expected argument of type "callable", got "' . gettype($func) . '"');
        }

        $this->beginTransaction();

        try {
            $return = call_user_func($func, $this);

            $this->commitTransaction($forceFlush);

            return $return;
        } catch (Exception $e) {
            $this->rollbackTransaction($closeOnException);

            throw $e;
        }
    }

    public function setLogger(LoggerInterface $logger): void
    {
        $this->logger = $logger;
    }

    public function getEntityManager(): EntityManagerInterface
    {
        return $this->entityManager;
    }
}
