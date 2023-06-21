<?php

namespace Dontdrinkandroot\DoctrineBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Dontdrinkandroot\Common\Instant;
use Dontdrinkandroot\DoctrineBundle\Type\InstantType;

/**
 * @psalm-require-implements UpdatedInstantEntityInterface
 */
trait UpdatedInstantEntityTrait
{
    #[ORM\Column(type: InstantType::NAME, nullable: false)]
    private Instant $updated;

    public function getUpdated(): Instant
    {
        return $this->updated;
    }
}
