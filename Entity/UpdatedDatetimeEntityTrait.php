<?php

namespace Dontdrinkandroot\DoctrineBundle\Entity;

use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

trait UpdatedDatetimeEntityTrait
{
    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: false)]
    public DateTime $updated;

    public function getUpdated(): DateTime
    {
        return $this->updated;
    }

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function generateUpdated(): void
    {
        $this->updated = new DateTime();
    }
}
