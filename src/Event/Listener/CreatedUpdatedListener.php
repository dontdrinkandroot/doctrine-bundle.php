<?php

namespace Dontdrinkandroot\DoctrineBundle\Event\Listener;

use DateTime;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Dontdrinkandroot\Common\DateUtils;
use Dontdrinkandroot\Common\ReflectionUtils;
use Dontdrinkandroot\DoctrineBundle\Entity\CreatedDatetimeEntityInterface;
use Dontdrinkandroot\DoctrineBundle\Entity\CreatedTimestampEntityInterface;
use Dontdrinkandroot\DoctrineBundle\Entity\UpdatedDatetimeEntityInterface;
use Dontdrinkandroot\DoctrineBundle\Entity\UpdatedTimestampEntityInterface;

class CreatedUpdatedListener
{
    public function prePersist(PrePersistEventArgs $eventArgs): void
    {
        $entity = $eventArgs->getObject();
        if ($entity instanceof CreatedDatetimeEntityInterface && property_exists($entity, 'created')) {
            ReflectionUtils::setPropertyValue($entity, 'created', new DateTime());
        }
        if ($entity instanceof CreatedTimestampEntityInterface && property_exists($entity, 'created')) {
            ReflectionUtils::setPropertyValue($entity, 'created', DateUtils::currentMillis());
        }
        if ($entity instanceof UpdatedDatetimeEntityInterface && property_exists($entity, 'updated')) {
            ReflectionUtils::setPropertyValue($entity, 'updated', new DateTime());
        }
        if ($entity instanceof UpdatedTimestampEntityInterface && property_exists($entity, 'updated')) {
            ReflectionUtils::setPropertyValue($entity, 'updated', DateUtils::currentMillis());
        }
    }

    public function preUpdate(PreUpdateEventArgs $eventArgs): void
    {
        $entity = $eventArgs->getObject();
        if ($entity instanceof UpdatedDatetimeEntityInterface && property_exists($entity, 'updated')) {
            ReflectionUtils::setPropertyValue($entity, 'updated', new DateTime());
        }
        if ($entity instanceof UpdatedTimestampEntityInterface && property_exists($entity, 'updated')) {
            ReflectionUtils::setPropertyValue($entity, 'updated', DateUtils::currentMillis());
        }
    }
}
