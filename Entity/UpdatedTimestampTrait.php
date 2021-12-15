<?php

namespace Dontdrinkandroot\DoctrineBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Dontdrinkandroot\Common\DateUtils;
use Dontdrinkandroot\DoctrineBundle\Type\MillisecondsType;

trait UpdatedTimestampTrait
{
    #[ORM\Column(type: MillisecondsType::NAME, nullable: false)]
    protected ?int $updated = null;

    public function getUpdated(): ?int
    {
        return $this->updated;
    }

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function generateUpdated(): void
    {
        $this->updated = DateUtils::currentMillis();
    }
}
