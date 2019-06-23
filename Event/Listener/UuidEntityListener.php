<?php

namespace Dontdrinkandroot\DoctrineBundle\Event\Listener;

use Doctrine\DBAL\DBALException;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Dontdrinkandroot\Entity\UuidEntityInterface;
use RuntimeException;

class UuidEntityListener
{
    const STRATEGY_DATABASE = 'database';

    const STRATEGY_RANDOM = 'random';

    protected $strategy = self::STRATEGY_RANDOM;

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if (is_a($entity, UuidEntityInterface::class)) {
            /** @var UuidEntityInterface $uuidEntity */
            $uuidEntity = $entity;
            if (null === $uuidEntity->getUuid()) {
                $uuid = $this->generateUuid($args->getEntityManager(), $this->strategy);
                $uuidEntity->setUuid($uuid);
            }
        }
    }

    /**
     * @param EntityManager $entityManager
     * @param string        $strategy
     *
     * @return string
     */
    public function generateUuid(EntityManager $entityManager, $strategy)
    {
        switch ($strategy) {
            case self::STRATEGY_RANDOM:
                return $this->generateRandomUuid();
            case self::STRATEGY_DATABASE:
                return $this->generateDatabaseUuid($entityManager);
        }

        throw new RuntimeException(sprintf('Strategy %s was not found', $strategy));
    }

    /**
     * Generates a random Uuid
     *
     * @return string
     */
    public function generateRandomUuid()
    {
        //TODO: Refactor into own class
        return sprintf(
            '%s-%s-%04x-%04x-%s',
            bin2hex(random_bytes(4)),
            bin2hex(random_bytes(2)),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            bin2hex(random_bytes(6))
        );
    }

    /**
     * @param EntityManager $entityManager
     *
     * @return bool|string
     * @throws DBALException
     */
    protected function generateDatabaseUuid(EntityManager $entityManager)
    {
        $conn = $entityManager->getConnection();
        $sql = 'SELECT ' . $conn->getDatabasePlatform()->getGuidExpression();

        return $conn->query($sql)->fetchColumn(0);
    }

    /**
     * @return string
     */
    public function getStrategy()
    {
        return $this->strategy;
    }

    /**
     * @param string $strategy
     */
    public function setStrategy($strategy)
    {
        $this->strategy = $strategy;
    }
}
