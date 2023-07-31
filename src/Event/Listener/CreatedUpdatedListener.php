<?php

namespace Dontdrinkandroot\DoctrineBundle\Event\Listener;

use DateTime;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Dontdrinkandroot\Common\DateUtils;
use Dontdrinkandroot\Common\Instant;
use Dontdrinkandroot\Common\ReflectionUtils;
use Dontdrinkandroot\DoctrineBundle\Entity\CreatedDatetimeTrait;
use Dontdrinkandroot\DoctrineBundle\Entity\CreatedTimestampTrait;
use Dontdrinkandroot\DoctrineBundle\Entity\CreatedTrait;
use Dontdrinkandroot\DoctrineBundle\Entity\UpdatedDatetimeTrait;
use Dontdrinkandroot\DoctrineBundle\Entity\UpdatedTimestampTrait;
use Dontdrinkandroot\DoctrineBundle\Entity\UpdatedTrait;

class CreatedUpdatedListener
{
    public function prePersist(PrePersistEventArgs $eventArgs): void
    {
        $entity = $eventArgs->getObject();
        $currentTimestamp = DateUtils::currentMillis();
        $currentDateTime = new DateTime();
        if (ReflectionUtils::usesTrait($entity, CreatedDatetimeTrait::class)) {
            ReflectionUtils::setPropertyValue($entity, 'created', $currentDateTime);
        }
        if (ReflectionUtils::usesTrait($entity, CreatedTimestampTrait::class)) {
            ReflectionUtils::setPropertyValue($entity, 'created', $currentTimestamp);
        }
        if (ReflectionUtils::usesTrait($entity, CreatedTrait::class)) {
            ReflectionUtils::setPropertyValue($entity, 'created', Instant::fromTimestamp($currentTimestamp));
        }
        if (ReflectionUtils::usesTrait($entity, UpdatedDatetimeTrait::class)) {
            ReflectionUtils::setPropertyValue($entity, 'updated', $currentDateTime);
        }
        if (ReflectionUtils::usesTrait($entity, UpdatedTimestampTrait::class)) {
            ReflectionUtils::setPropertyValue($entity, 'updated', $currentTimestamp);
        }
        if (ReflectionUtils::usesTrait($entity, UpdatedTrait::class)) {
            ReflectionUtils::setPropertyValue($entity, 'updated', Instant::fromTimestamp($currentTimestamp));
        }
    }

    public function preUpdate(PreUpdateEventArgs $eventArgs): void
    {
        $entity = $eventArgs->getObject();
        if (ReflectionUtils::usesTrait($entity, UpdatedDatetimeTrait::class)) {
            ReflectionUtils::setPropertyValue($entity, 'updated', new DateTime());
        }
        if (ReflectionUtils::usesTrait($entity, UpdatedTimestampTrait::class)) {
            ReflectionUtils::setPropertyValue($entity, 'updated', DateUtils::currentMillis());
        }
        if (ReflectionUtils::usesTrait($entity, UpdatedTrait::class)) {
            ReflectionUtils::setPropertyValue($entity, 'updated', Instant::now());
        }
    }
}
