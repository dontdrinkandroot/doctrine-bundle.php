<?php

namespace Dontdrinkandroot\DoctrineBundle\Event\Listener;

use DateTime;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Dontdrinkandroot\Common\DateUtils;
use Dontdrinkandroot\DoctrineBundle\Entity\CreatedEntityInterface;
use Dontdrinkandroot\DoctrineBundle\Entity\CreatedTimestampEntityInterface;

class CreatedEntityListener
{
    public function prePersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getEntity();

        if (
            is_a($entity, CreatedEntityInterface::class)
            && null === $entity->getCreated()
        ) {
            $entity->setCreated(new DateTime());
        }

        if (
            is_a($entity, CreatedTimestampEntityInterface::class)
            && null === $entity->getCreatedTimestamp()
        ) {
            $entity->setCreatedTimestamp(DateUtils::currentMillis());
        }
    }
}
