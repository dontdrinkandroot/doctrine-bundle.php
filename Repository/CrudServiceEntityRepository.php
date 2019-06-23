<?php

namespace Dontdrinkandroot\DoctrineBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepositoryInterface;
use Dontdrinkandroot\Repository\OrmEntityRepository;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
class CrudServiceEntityRepository extends OrmEntityRepository implements ServiceEntityRepositoryInterface
{
}
