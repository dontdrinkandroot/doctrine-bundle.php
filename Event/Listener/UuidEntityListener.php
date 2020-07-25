<?php

namespace Dontdrinkandroot\DoctrineBundle\Event\Listener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Dontdrinkandroot\DoctrineBundle\Entity\UuidEntityInterface;
use Ramsey\Uuid\Uuid;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
class UuidEntityListener
{
    public function prePersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getEntity();

        if (is_a($entity, UuidEntityInterface::class)) {
            if (null === $entity->getUuid()) {
                $entity->setUuid(Uuid::uuid4());
            }
        }
    }
}
