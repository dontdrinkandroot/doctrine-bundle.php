<?php

namespace Dontdrinkandroot\DoctrineBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepositoryInterface;
use Dontdrinkandroot\Repository\TransactionalUuidCrudRepository;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
class TransactionalUuidCrudRepositoryService extends TransactionalUuidCrudRepository
    implements ServiceEntityRepositoryInterface
{
}
