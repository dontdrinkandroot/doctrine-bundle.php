<?php

namespace Dontdrinkandroot\DoctrineBundle\Event\Listener;

use Doctrine\ORM\Event\PrePersistEventArgs;
use Dontdrinkandroot\Common\ReflectionUtils;
use Dontdrinkandroot\DoctrineBundle\Entity\UuidTrait;
use Symfony\Component\Uid\Factory\UuidFactory;

class UuidListener
{
    public function __construct(private readonly UuidFactory $uuidFactory)
    {
    }

    public function prePersist(PrePersistEventArgs $eventArgs): void
    {
        $entity = $eventArgs->getObject();
        if (ReflectionUtils::usesTrait($entity, UuidTrait::class) && !$entity->hasUuid()) {
            ReflectionUtils::setPropertyValue($entity, 'uuid', $this->uuidFactory->create());
        }
    }
}
