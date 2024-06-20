<?php

namespace Dontdrinkandroot\DoctrineBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Dontdrinkandroot\Common\Instant;
use Dontdrinkandroot\DoctrineBundle\Type\InstantType;
use LogicException;
use Override;

/**
 * @psalm-require-implements UpdatedInterface
 */
trait UpdatedTrait
{
    #[ORM\Column(type: InstantType::NAME, nullable: false)]
    protected ?Instant $updated = null;

    #[Override]
    public function getUpdated(): Instant
    {
        return $this->updated ?? throw new LogicException('Entity was not persisted yet');
    }

    public function hasUpdated(): bool
    {
        return null !== $this->updated;
    }
}
