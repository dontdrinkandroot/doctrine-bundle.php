<?php

namespace Dontdrinkandroot\DoctrineBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

/**
 * @psalm-require-implements UuidInterface
 */
trait UuidTrait
{
    #[ORM\Column(type: 'uuid', unique: true, nullable: false)]
    public Uuid $uuid;

    public function getUuid(): Uuid
    {
        return $this->uuid;
    }

    public function hasUuid(): bool
    {
        return isset($this->uuid);
    }
}
