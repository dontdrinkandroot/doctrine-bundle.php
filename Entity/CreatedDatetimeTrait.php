<?php

namespace Dontdrinkandroot\DoctrineBundle\Entity;

use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

trait CreatedDatetimeTrait
{
    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: false)]
    protected ?DateTime $created = null;

    public function getCreated(): ?DateTime
    {
        return $this->created;
    }

    #[ORM\PrePersist]
    public function generateCreated(): void
    {
        $this->created = new DateTime();
    }
}
