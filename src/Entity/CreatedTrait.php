<?php

namespace Dontdrinkandroot\DoctrineBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Dontdrinkandroot\Common\Instant;
use Dontdrinkandroot\DoctrineBundle\Type\InstantType;
use LogicException;

/**
 * @psalm-require-implements CreatedInterface
 */
trait CreatedTrait
{
    #[ORM\Column(type: InstantType::NAME, nullable: false)]
    protected ?Instant $created = null;

    public function getCreated(): Instant
    {
        return $this->created ?? throw new LogicException('Entity was not persisted yet');
    }

    public function hasCreated(): bool
    {
        return null !== $this->created;
    }
}
