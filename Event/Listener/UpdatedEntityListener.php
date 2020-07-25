<?php

namespace Dontdrinkandroot\DoctrineBundle\Event\Listener;

use DateTime;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Dontdrinkandroot\DoctrineBundle\Entity\UpdatedEntityInterface;
use Dontdrinkandroot\DoctrineBundle\Entity\UpdatedTimestampEntityInterface;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
class UpdatedEntityListener
{
    public function prePersist(LifecycleEventArgs $args): void
    {
        $this->checkAndSetUpdated($args);
    }

    public function preUpdate(LifecycleEventArgs $args): void
    {
        $this->checkAndSetUpdated($args);
    }

    protected function checkAndSetUpdated(LifecycleEventArgs $args): void
    {
        $entity = $args->getEntity();

        if (is_a($entity, UpdatedEntityInterface::class)) {
            $entity->setUpdated(new DateTime());
        }

        if (is_a($entity, UpdatedTimestampEntityInterface::class)) {
            if (null === $entity->getUpdatedTimestamp()) {
                $entity->setUpdatedTimestamp((int)(microtime(true) * 1000));
            }
        }
    }
}
