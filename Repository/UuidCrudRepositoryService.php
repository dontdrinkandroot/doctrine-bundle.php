<?php

namespace Dontdrinkandroot\DoctrineBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepositoryInterface;
use Dontdrinkandroot\Repository\UuidCrudRepository;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
class UuidCrudRepositoryService extends UuidCrudRepository implements ServiceEntityRepositoryInterface
{
}
