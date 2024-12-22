<?php

namespace Dontdrinkandroot\DoctrineBundle\Event\Listener;

use DateTime;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Dontdrinkandroot\Common\DateUtils;
use Dontdrinkandroot\Common\Instant;
use Dontdrinkandroot\Common\ReflectionUtils;
use Dontdrinkandroot\DoctrineBundle\Entity\CreatedAtTrait;
use Dontdrinkandroot\DoctrineBundle\Entity\UpdatedAtTrait;

class CreatedUpdatedListener
{
    public function prePersist(PrePersistEventArgs $eventArgs): void
    {
        $entity = $eventArgs->getObject();
        $currentTimestamp = DateUtils::currentMillis();
        $currentDateTime = new DateTime();

        if (
            ReflectionUtils::usesTrait($entity, CreatedAtTrait::class)
            && !$entity->hasCreatedAt()
        ) {
            ReflectionUtils::setPropertyValue($entity, 'createdAt', Instant::fromTimestamp($currentTimestamp));
        }

        if (ReflectionUtils::usesTrait($entity, UpdatedAtTrait::class)) {
            ReflectionUtils::setPropertyValue($entity, 'updatedAt', Instant::fromTimestamp($currentTimestamp));
        }
    }

    public function preUpdate(PreUpdateEventArgs $eventArgs): void
    {
        $entity = $eventArgs->getObject();

        if (ReflectionUtils::usesTrait($entity, UpdatedAtTrait::class)) {
            ReflectionUtils::setPropertyValue($entity, 'updatedAt', Instant::now());
        }
    }
}
