<?php

namespace Dontdrinkandroot\DoctrineBundle\Tests\TestApp\Repository;

use Doctrine\Persistence\ManagerRegistry;
use Dontdrinkandroot\DoctrineBundle\Repository\TransactionalCrudRepository;
use Dontdrinkandroot\DoctrineBundle\Service\TransactionManager\TransactionManagerRegistry;
use Dontdrinkandroot\DoctrineBundle\Tests\TestApp\Entity\ExampleEntity;

/**
 * @extends TransactionalCrudRepository<ExampleEntity>
 */
class ExampleEntityRepository extends TransactionalCrudRepository
{
    public function __construct(ManagerRegistry $registry, TransactionManagerRegistry $transactionManagerRegistry)
    {
        parent::__construct($registry, ExampleEntity::class, $transactionManagerRegistry);
    }
}
