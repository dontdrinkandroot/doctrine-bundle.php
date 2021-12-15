<?php

namespace Dontdrinkandroot\DoctrineBundle\Event\Listener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Dontdrinkandroot\DoctrineBundle\Entity\UuidEntityInterface;
use Symfony\Component\Uid\Uuid;

class UuidEntityListener
{
    public function prePersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getEntity();

        if (is_a($entity, UuidEntityInterface::class)) {
            if (null === $entity->getUuid()) {
                $entity->setUuid(Uuid::v4());
            }
        }
    }
}
