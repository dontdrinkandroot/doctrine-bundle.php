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
    public function find($id, $lockMode = null, $lockVersion = null): ?object
    {
        return $this->getTransactionManager()->transactional(fn() => parent::find($id, $lockMode, $lockVersion));
    }

    #[Override]
    public function fetch($id, $lockMode = null, $lockVersion = null): object
    {
        return $this->getTransactionManager()->transactional(
            fn(): object => parent::fetch($id, $lockMode, $lockVersion)
        );
    }

    #[Override]
    public function findAll(): array
    {
        return $this->getTransactionManager()->transactional(fn() => parent::findAll());
    }

    #[Override]
    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null): array
    {
        return $this->getTransactionManager()->transactional(
            fn() => parent::findBy($criteria, $orderBy, $limit, $offset)
        );
    }

    #[Override]
    public function findOneBy(array $criteria, array $orderBy = null): ?object
    {
        return $this->getTransactionManager()->transactional(fn() => parent::findOneBy($criteria, $orderBy));
    }

    #[Override]
    public function fetchOneBy(array $criteria, array $orderBy = null): object
    {
        return $this->getTransactionManager()->transactional(
            fn(): object => parent::fetchOneBy($criteria, $orderBy)
        );
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

    #[Override]
    public function findPaginatedBy(
        int $page = 1,
        int $perPage = 10,
        array $criteria = [],
        array $orderBy = null
    ): Paginator {
        return $this->transactionManager->transactional(
            fn(): Paginator => parent::findPaginatedBy(
                $page,
                $perPage,
                $criteria,
                $orderBy
            )
        );
    }

    public function getTransactionManager(): TransactionManager
    {
        return $this->transactionManager;
    }
}
