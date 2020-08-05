<?php

namespace Dontdrinkandroot\DoctrineBundle\Repository;

use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;
use Dontdrinkandroot\DoctrineBundle\Service\TransactionManager\TransactionManager;
use Dontdrinkandroot\DoctrineBundle\Service\TransactionManager\TransactionManagerRegistry;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
class TransactionalCrudRepository extends CrudRepository
{
    private TransactionManager $transactionManager;

    public function __construct(
        ManagerRegistry $registry,
        string $entityClass,
        TransactionManagerRegistry $transactionManagerRegistry
    ) {
        parent::__construct($registry, $entityClass);
        $this->transactionManager = $transactionManagerRegistry->getByEntityManager($this->_em);
    }

    /**
     * {@inheritdoc}
     */
    public function find($id, $lockMode = null, $lockVersion = null)
    {
        return $this->getTransactionManager()->transactional(fn() => parent::find($id, $lockMode, $lockVersion));
    }

    /**
     * {@inheritdoc}
     */
    public function findAll()
    {
        return $this->getTransactionManager()->transactional(fn() => parent::findAll());
    }

    /**
     * {@inheritdoc}
     */
    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
    {
        return $this->getTransactionManager()->transactional(
            fn() => parent::findBy($criteria, $orderBy, $limit, $offset)
        );
    }

    /**
     * {@inheritdoc}
     */
    public function create($entity, bool $flush = true)
    {
        $this->transactionManager->transactional(fn() => $this->getEntityManager()->persist($entity), $flush);
    }

    /**
     * {@inheritdoc}
     */
    public function delete($entity, bool $flush = true)
    {
        $this->transactionManager->transactional(fn() => $this->getEntityManager()->remove($entity), $flush);
    }

    /**
     * {@inheritdoc}
     */
    public function findPaginatedBy(
        int $page = 1,
        int $perPage = 10,
        array $criteria = [],
        array $orderBy = null
    ): Paginator {
        return $this->transactionManager->transactional(
            fn() => parent::findPaginatedBy($page, $perPage, $criteria, $orderBy)
        );
    }

    public function getTransactionManager(): TransactionManager
    {
        return $this->transactionManager;
    }
}
