<?php

namespace Dontdrinkandroot\DoctrineBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Dontdrinkandroot\Common\Instant;
use Dontdrinkandroot\DoctrineBundle\Type\InstantType;
use LogicException;
use Override;

/**
 * @phpstan-require-implements UpdatedAtColumnInterface
 */
trait UpdatedAtColumnTrait
{
    #[ORM\Column(type: InstantType::NAME, nullable: false)]
    protected ?Instant $updatedAt = null;

    #[Override]
    public function getUpdatedAt(): Instant
    {
        return $this->updatedAt ?? throw new LogicException('Entity was not persisted yet');
    }

    #[Override]
    public function hasUpdatedAt(): bool
    {
        return null !== $this->updatedAt;
    }
}
