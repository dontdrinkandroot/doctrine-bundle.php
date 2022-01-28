<?php

namespace Dontdrinkandroot\DoctrineBundle\Entity;

use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

trait CreatedDatetimeEntityTrait
{
    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: false)]
    public DateTime $created;

    public function getCreated(): DateTime
    {
        return $this->created;
    }

    #[ORM\PrePersist]
    public function generateCreated(): void
    {
        $this->created = new DateTime();
    }
}
