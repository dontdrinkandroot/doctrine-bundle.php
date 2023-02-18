<?php

namespace Dontdrinkandroot\DoctrineBundle\Event\Listener;

use Doctrine\ORM\Event\PrePersistEventArgs;
use Dontdrinkandroot\Common\ReflectionUtils;
use Dontdrinkandroot\DoctrineBundle\Entity\UuidEntityTrait;
use Symfony\Component\Uid\Uuid;

class UuidListener
{
    public function prePersist(PrePersistEventArgs $eventArgs): void
    {
        $entity = $eventArgs->getObject();
        if (in_array(UuidEntityTrait::class, class_uses($entity), true) && !$entity->hasUuid()) {
            ReflectionUtils::setPropertyValue($entity, 'uuid', Uuid::v4());
        }
    }
}
