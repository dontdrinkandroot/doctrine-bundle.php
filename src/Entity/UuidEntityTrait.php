<?php

namespace Dontdrinkandroot\DoctrineBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

trait UuidEntityTrait
{
    #[ORM\Column(type: 'uuid', unique: true, nullable: false)]
    public Uuid $uuid;

    public function getUuid(): Uuid
    {
        return $this->uuid;
    }

    #[ORM\PrePersist]
    public function generateUuid(): Uuid
    {
        if (!isset($this->uuid)) {
            $this->uuid = Uuid::v4();
        }

        return $this->uuid;
    }
}
