<?php

namespace Dontdrinkandroot\DoctrineBundle\Entity;

use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Dontdrinkandroot\Common\DateUtils;

trait CreatedTimestampEntityTrait
{
    #[ORM\Column(type: Types::BIGINT, nullable: false, options: ["unsigned" => true])]
    public int $created;

    public function getCreated(): int
    {
        return $this->created;
    }

    public function getCreatedDateTime(): DateTimeInterface
    {
        return DateUtils::fromMillis($this->created);
    }

    #[ORM\PrePersist]
    public function generateCreated(): void
    {
        /* Checking it is already set in case it was assigned manually */
        if (!isset($this->created)) {
            $this->created = DateUtils::currentMillis();
        }
    }
}
