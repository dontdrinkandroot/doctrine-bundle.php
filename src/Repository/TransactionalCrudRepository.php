<?php

namespace Dontdrinkandroot\DoctrineBundle\Repository;

use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;
use Dontdrinkandroot\DoctrineBundle\Service\TransactionManager\TransactionManager;
use Dontdrinkandroot\DoctrineBundle\Service\TransactionManager\TransactionManagerRegistry;
use Override;

/**
 * @template T of object
 * @extends CrudRepository<T>
 */
class TransactionalCrudRepository extends CrudRepository
{
    private readonly TransactionManager $transactionManager;

    /**
     * @param ManagerRegistry            $registry
     * @param class-string<T>            $entityClass
     */
    public function __construct(
        ManagerRegistry $registry,
        string $entityClass,
        TransactionManagerRegistry $transactionManagerRegistry
    ) {
        parent::__construct($registry, $entityClass);
        $this->transactionManager = $transactionManagerRegistry->getByEntityManager($this->getEntityManager());
    }

    #[Override]
    public function create($entity, bool $flush = true): void
    {
        $this->transactionManager->transactional(fn() => parent::create($entity), $flush);
    }

    #[Override]
    public function delete($entity, bool $flush = true): void
    {
        $this->transactionManager->transactional(fn() => parent::delete($entity), $flush);
    }

    public function getTransactionManager(): TransactionManager
    {
        return $this->transactionManager;
    }
}
