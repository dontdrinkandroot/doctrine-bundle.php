<?php

namespace Dontdrinkandroot\DoctrineBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use LogicException;
use Override;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

/**
 * @phpstan-require-implements UuidColumnInterface
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

    #[Override]
    public function hasUuid(): bool
    {
        return null !== $this->uuid;
    }

    protected function setUuid(Uuid $uuid): void
    {
        $this->uuid = $uuid;
    }
}
