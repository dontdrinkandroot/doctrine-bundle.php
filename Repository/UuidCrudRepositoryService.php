<?php

namespace Dontdrinkandroot\DoctrineBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepositoryInterface;
use Doctrine\Common\Persistence\ManagerRegistry;
use Dontdrinkandroot\Repository\UuidCrudRepository;
use LogicException;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
class UuidCrudRepositoryService extends UuidCrudRepository implements ServiceEntityRepositoryInterface
{
    public function __construct(ManagerRegistry $registry, $entityClass)
    {
        $manager = $registry->getManagerForClass($entityClass);

        if ($manager === null) {
            throw new LogicException(
                sprintf(
                    'Could not find the entity manager for class "%s". Check your Doctrine configuration to make sure it is configured to load this entity’s metadata.',
                    $entityClass
                )
            );
        }

        parent::__construct($manager, $manager->getClassMetadata($entityClass));
    }
}
