<?php

namespace Dontdrinkandroot\DoctrineBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use LogicException;
use Override;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

/**
 * @psalm-require-implements UuidIdentifiedInterface
 */
trait UuidTrait
{
    #[ORM\Column(type: UuidType::NAME, unique: true, nullable: false)]
    public ?Uuid $uuid = null;

    #[Override]
    public function getUuid(): Uuid
    {
        return $this->uuid ?? throw new LogicException('Entity was not persisted yet');
    }

    public function hasUuid(): bool
    {
        return null !== $this->uuid;
    }
}
