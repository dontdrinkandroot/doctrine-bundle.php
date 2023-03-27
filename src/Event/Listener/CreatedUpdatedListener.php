<?php

namespace Dontdrinkandroot\DoctrineBundle\Event\Listener;

use DateTime;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Dontdrinkandroot\Common\DateUtils;
use Dontdrinkandroot\Common\ReflectionUtils;
use Dontdrinkandroot\DoctrineBundle\Entity\CreatedDatetimeEntityTrait;
use Dontdrinkandroot\DoctrineBundle\Entity\CreatedTimestampEntityTrait;
use Dontdrinkandroot\DoctrineBundle\Entity\UpdatedDatetimeEntityTrait;
use Dontdrinkandroot\DoctrineBundle\Entity\UpdatedTimestampEntityTrait;

class CreatedUpdatedListener
{
    public function prePersist(PrePersistEventArgs $eventArgs): void
    {
        $entity = $eventArgs->getObject();
        $currentTimestamp = DateUtils::currentMillis();
        $currentDateTime = new DateTime();
        if (ReflectionUtils::usesTrait($entity, CreatedDatetimeEntityTrait::class)) {
            ReflectionUtils::setPropertyValue($entity, 'created', $currentDateTime);
        }
        if (ReflectionUtils::usesTrait($entity, CreatedTimestampEntityTrait::class)) {
            ReflectionUtils::setPropertyValue($entity, 'created', $currentTimestamp);
        }
        if (ReflectionUtils::usesTrait($entity, UpdatedDatetimeEntityTrait::class)) {
            ReflectionUtils::setPropertyValue($entity, 'updated', $currentDateTime);
        }
        if (ReflectionUtils::usesTrait($entity, UpdatedTimestampEntityTrait::class)) {
            ReflectionUtils::setPropertyValue($entity, 'updated', $currentTimestamp);
        }
    }

    public function preUpdate(PreUpdateEventArgs $eventArgs): void
    {
        $entity = $eventArgs->getObject();
        if (ReflectionUtils::usesTrait($entity, UpdatedDatetimeEntityTrait::class)) {
            ReflectionUtils::setPropertyValue($entity, 'updated', new DateTime());
        }
        if (ReflectionUtils::usesTrait($entity, UpdatedTimestampEntityTrait::class)) {
            ReflectionUtils::setPropertyValue($entity, 'updated', DateUtils::currentMillis());
        }
    }
}
