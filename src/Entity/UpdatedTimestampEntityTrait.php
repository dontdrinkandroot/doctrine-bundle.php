<?php

namespace Dontdrinkandroot\DoctrineBundle\Entity;

use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Dontdrinkandroot\Common\DateUtils;

trait UpdatedTimestampEntityTrait
{
    #[ORM\Column(type: Types::BIGINT, nullable: false, options: ["unsigned" => true])]
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