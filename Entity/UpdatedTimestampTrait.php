<?php

namespace Dontdrinkandroot\DoctrineBundle\Entity;

use DateTime;
use DateTimeInterface;
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

    public function getUpdatedDateTime(): ?DateTimeInterface
    {
        if (null === $this->updated) {
            return null;
        }
        return new DateTime('@' . ($this->updated / 1000));
    }

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function generateUpdated(): void
    {
        $this->updated = DateUtils::currentMillis();
    }
}
