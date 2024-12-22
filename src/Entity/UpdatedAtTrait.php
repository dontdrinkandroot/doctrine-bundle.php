<?php

namespace Dontdrinkandroot\DoctrineBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Dontdrinkandroot\Common\Instant;
use Dontdrinkandroot\DoctrineBundle\Type\InstantType;
use LogicException;
use Override;

/**
 * @psalm-require-implements UpdatedAtInterface
 */
trait UpdatedAtTrait
{
    #[ORM\Column(type: InstantType::NAME, nullable: false)]
    protected ?Instant $updatedAt = null;

    #[Override]
    public function getUpdatedAt(): Instant
    {
        return $this->updatedAt ?? throw new LogicException('Entity was not persisted yet');
    }

    public function hasUpdatedAt(): bool
    {
        return null !== $this->updatedAt;
    }
}
