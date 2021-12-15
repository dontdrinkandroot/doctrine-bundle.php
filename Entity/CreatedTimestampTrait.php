<?php

namespace Dontdrinkandroot\DoctrineBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Dontdrinkandroot\Common\DateUtils;
use Dontdrinkandroot\DoctrineBundle\Type\MillisecondsType;

trait CreatedTimestampTrait
{
    #[ORM\Column(type: MillisecondsType::NAME, nullable: false)]
    protected ?int $created = null;

    public function getCreated(): ?int
    {
        return $this->created;
    }

    #[ORM\PrePersist]
    public function generateCreated(): void
    {
        if (null === $this->created) {
            $this->created = DateUtils::currentMillis();
        }
    }
}
