<?php

namespace Dontdrinkandroot\DoctrineBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Dontdrinkandroot\Common\Instant;
use Dontdrinkandroot\DoctrineBundle\Type\InstantType;
use LogicException;
use Override;

/**
 * @phpstan-require-implements CreatedAtColumnInterface
 */
trait CreatedAtColumnTrait
{
    #[ORM\Column(type: InstantType::NAME, nullable: false)]
    protected ?Instant $createdAt = null;

    #[Override]
    public function getCreatedAt(): Instant
    {
        return $this->createdAt ?? throw new LogicException('Entity was not persisted yet');
    }

    #[Override]
    public function hasCreatedAt(): bool
    {
        return null !== $this->createdAt;
    }
}
