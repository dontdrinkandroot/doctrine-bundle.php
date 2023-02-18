<?php

namespace Dontdrinkandroot\DoctrineBundle\Event\Listener;

use Doctrine\ORM\Event\PrePersistEventArgs;
use Dontdrinkandroot\Common\ReflectionUtils;
use Dontdrinkandroot\DoctrineBundle\Entity\UuidEntityInterface;
use Symfony\Component\Uid\Uuid;

class UuidListener
{
    public function prePersist(PrePersistEventArgs $eventArgs): void
    {
        $entity = $eventArgs->getObject();
        if ($entity instanceof UuidEntityInterface && property_exists($entity, 'uuid')) {
            ReflectionUtils::setPropertyValue($entity, 'uuid', Uuid::v4());
        }
    }
}
