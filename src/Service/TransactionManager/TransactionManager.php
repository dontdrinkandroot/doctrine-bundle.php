<?php

namespace Dontdrinkandroot\DoctrineBundle\Service\TransactionManager;

use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class TransactionManager
{
    private LoggerInterface $logger;

    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
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

        /* EntityManager closed */
        if (!$this->getEntityManager()->isOpen()) {
            $this->logger->warning('The EntityManager was already closed');

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

    /**
     * @template T
     *
     * @param callable(TransactionManager):T $func
     *
     * @return T
     * @throws Exception
     */
    public function transactional(callable $func, bool $forceFlush = false, bool $closeOnException = true)
    {
        $this->beginTransaction();

        try {
            $return = $func($this);

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
