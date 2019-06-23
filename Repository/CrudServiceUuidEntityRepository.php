<?php

namespace Dontdrinkandroot\DoctrineBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepositoryInterface;
use Dontdrinkandroot\Repository\OrmUuidEntityRepository;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
class CrudServiceUuidEntityRepository extends OrmUuidEntityRepository implements ServiceEntityRepositoryInterface
{
}
