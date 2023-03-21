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
        if (in_array(CreatedDatetimeEntityTrait::class, class_uses($entity), true)) {
            ReflectionUtils::setPropertyValue($entity, 'created', $currentDateTime);
        }
        if (in_array(CreatedTimestampEntityTrait::class, class_uses($entity), true)) {
            ReflectionUtils::setPropertyValue($entity, 'created', $currentTimestamp);
        }
        if (in_array(UpdatedDatetimeEntityTrait::class, class_uses($entity), true)) {
            ReflectionUtils::setPropertyValue($entity, 'updated', $currentDateTime);
        }
        if (in_array(UpdatedTimestampEntityTrait::class, class_uses($entity), true)) {
            ReflectionUtils::setPropertyValue($entity, 'updated', $currentTimestamp);
        }
    }

    public function preUpdate(PreUpdateEventArgs $eventArgs): void
    {
        $entity = $eventArgs->getObject();
        if (in_array(UpdatedDatetimeEntityTrait::class, class_uses($entity), true)) {
            ReflectionUtils::setPropertyValue($entity, 'updated', new DateTime());
        }
        if (in_array(UpdatedTimestampEntityTrait::class, class_uses($entity), true)) {
            ReflectionUtils::setPropertyValue($entity, 'updated', DateUtils::currentMillis());
        }
    }
}
