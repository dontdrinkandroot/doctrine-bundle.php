<?php

namespace Dontdrinkandroot\DoctrineBundle\Event\Listener;

use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Dontdrinkandroot\Common\DateUtils;
use Dontdrinkandroot\Common\Instant;
use Dontdrinkandroot\Common\ReflectionUtils;
use Dontdrinkandroot\DoctrineBundle\Entity\CreatedAtColumnInterface;
use Dontdrinkandroot\DoctrineBundle\Entity\UpdatedAtColumnInterface;

class CreatedUpdatedListener
{
    public function prePersist(PrePersistEventArgs $eventArgs): void
    {
        $entity = $eventArgs->getObject();
        $currentTimestamp = DateUtils::currentMillis();

        if (
            is_a($entity, CreatedAtColumnInterface::class, true)
            && !$entity->hasCreatedAt()
        ) {
            ReflectionUtils::setPropertyValue($entity, 'createdAt', Instant::fromTimestamp($currentTimestamp));
        }

        if (is_a($entity, UpdatedAtColumnInterface::class, true)) {
            ReflectionUtils::setPropertyValue($entity, 'updatedAt', Instant::fromTimestamp($currentTimestamp));
        }
    }

    public function preUpdate(PreUpdateEventArgs $eventArgs): void
    {
        $entity = $eventArgs->getObject();

        if (is_a($entity, UpdatedAtColumnInterface::class, true)) {
            ReflectionUtils::setPropertyValue($entity, 'updatedAt', Instant::now());
        }
    }
}
