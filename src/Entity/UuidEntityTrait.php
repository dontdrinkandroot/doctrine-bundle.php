<?php

namespace Dontdrinkandroot\DoctrineBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

trait UuidEntityTrait
{
    #[ORM\Column(type: 'uuid', unique: true, nullable: false)]
    private Uuid $uuid;

    public function getUuid(): Uuid
    {
        return $this->uuid;
    }
}
