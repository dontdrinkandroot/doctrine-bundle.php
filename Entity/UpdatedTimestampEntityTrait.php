<?php

namespace Dontdrinkandroot\DoctrineBundle\Entity;

use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Dontdrinkandroot\Common\DateUtils;
use Dontdrinkandroot\DoctrineBundle\Type\MillisecondsType;

trait UpdatedTimestampEntityTrait
{
    #[ORM\Column(type: MillisecondsType::NAME, nullable: false, options: ["unsigned" => true])]
    public int $updated;

    public function getUpdated(): int
    {
        return $this->updated;
    }

    public function getUpdatedDateTime(): DateTimeInterface
    {
        return DateUtils::fromMillis($this->updated);
    }

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function generateUpdated(): void
    {
        $this->updated = DateUtils::currentMillis();
    }
}
