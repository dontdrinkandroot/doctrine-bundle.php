<?php

namespace Dontdrinkandroot\DoctrineBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Dontdrinkandroot\Common\Instant;
use Dontdrinkandroot\DoctrineBundle\Type\InstantType;

/**
 * @psalm-require-implements CreatedInstantEntityInterface
 */
trait CreatedInstantEntityTrait
{
    #[ORM\Column(type: InstantType::NAME, nullable: false)]
    protected Instant $created;

    public function getCreated(): Instant
    {
        return $this->created;
    }
}
