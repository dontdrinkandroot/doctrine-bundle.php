<?php

namespace Dontdrinkandroot\DoctrineBundle\Tests\TestApp\Repository;

use Doctrine\Persistence\ManagerRegistry;
use Dontdrinkandroot\DoctrineBundle\Repository\TransactionalCrudRepository;
use Dontdrinkandroot\DoctrineBundle\Service\TransactionManager\TransactionManagerRegistry;
use Dontdrinkandroot\DoctrineBundle\Tests\TestApp\Entity\Album;

/**
 * @extends TransactionalCrudRepository<Album>
 */
class AlbumRepository extends TransactionalCrudRepository
{
    public function __construct(ManagerRegistry $registry, TransactionManagerRegistry $transactionManagerRegistry)
    {
        parent::__construct($registry, Album::class, $transactionManagerRegistry);
    }
}
