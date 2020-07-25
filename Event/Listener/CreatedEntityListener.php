<?php

namespace Dontdrinkandroot\DoctrineBundle\Event\Listener;

use DateTime;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Dontdrinkandroot\DoctrineBundle\Entity\CreatedEntityInterface;
use Dontdrinkandroot\DoctrineBundle\Entity\CreatedTimestampEntityInterface;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
class CreatedEntityListener
{
    public function prePersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getEntity();

        if (is_a($entity, CreatedEntityInterface::class)) {
            if (null === $entity->getCreated()) {
                $entity->setCreated(new DateTime());
            }
        }

        if (is_a($entity, CreatedTimestampEntityInterface::class)) {
            if (null === $entity->getCreatedTimestamp()) {
                $entity->setCreatedTimestamp((int)(microtime(true) * 1000));
            }
        }
    }
}
