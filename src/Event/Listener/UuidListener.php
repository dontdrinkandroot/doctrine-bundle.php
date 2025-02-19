<?php

namespace Dontdrinkandroot\DoctrineBundle\Event\Listener;

use Doctrine\ORM\Event\PrePersistEventArgs;
use Dontdrinkandroot\Common\ReflectionUtils;
use Dontdrinkandroot\DoctrineBundle\Entity\UuidColumnInterface;
use Symfony\Component\Uid\Factory\UuidFactory;

class UuidListener
{
    public function __construct(private readonly UuidFactory $uuidFactory)
    {
    }

    public function prePersist(PrePersistEventArgs $eventArgs): void
    {
        $entity = $eventArgs->getObject();
        if (is_a($entity, UuidColumnInterface::class, true) && !$entity->hasUuid()) {
            ReflectionUtils::setPropertyValue($entity, 'uuid', $this->uuidFactory->create());
        }
    }
}
