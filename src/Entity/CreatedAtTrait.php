<?php

namespace Dontdrinkandroot\DoctrineBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Dontdrinkandroot\Common\Instant;
use Dontdrinkandroot\DoctrineBundle\Type\InstantType;
use LogicException;
use Override;

/**
 * @psalm-require-implements CreatedAtInterface
 */
trait CreatedAtTrait
{
    #[ORM\Column(type: InstantType::NAME, nullable: false)]
    protected ?Instant $createdAt = null;

    #[Override]
    public function getCreatedAt(): Instant
    {
        return $this->createdAt ?? throw new LogicException('Entity was not persisted yet');
    }

    public function hasCreatedAt(): bool
    {
        return null !== $this->createdAt;
    }
}
