<?php

namespace Dontdrinkandroot\DoctrineBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Dontdrinkandroot\Common\Instant;
use Dontdrinkandroot\DoctrineBundle\Type\InstantType;

/**
 * @psalm-require-implements UpdatedInterface
 */
trait UpdatedTrait
{
    #[ORM\Column(type: InstantType::NAME, nullable: false)]
    protected Instant $updated;

    public function getUpdated(): Instant
    {
        return $this->updated;
    }
}
