<?php

namespace Dontdrinkandroot\DoctrineBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

trait UuidEntityTrait
{
    #[ORM\Column(type: 'uuid', unique: true, nullable: false)]
    protected ?Uuid $uuid = null;

    public function getUuid(): ?Uuid
    {
        return $this->uuid;
    }

    #[ORM\PrePersist]
    public function generateUuid(): void
    {
        if (null === $this->uuid) {
            $this->uuid = Uuid::v4();
        }
    }
}
