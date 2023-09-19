<?php

namespace Dontdrinkandroot\DoctrineBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Dontdrinkandroot\Common\Instant;
use Dontdrinkandroot\DoctrineBundle\Type\InstantType;
use LogicException;

/**
 * @psalm-require-implements UpdatedInterface
 */
trait UpdatedTrait
{
    #[ORM\Column(type: InstantType::NAME, nullable: false)]
    protected ?Instant $updated = null;

    public function getUpdated(): Instant
    {
        return $this->updated ?? throw new LogicException('Entity was not persisted yet');
    }

    public function hasUpdated(): bool
    {
        return null !== $this->updated;
    }
}
