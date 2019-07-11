<?php

namespace Dontdrinkandroot\DoctrineBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepositoryInterface;
use Dontdrinkandroot\Repository\CrudRepository;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
class CrudRepositoryService extends CrudRepository implements ServiceEntityRepositoryInterface
{
}
