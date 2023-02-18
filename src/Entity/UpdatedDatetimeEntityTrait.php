<?php

namespace Dontdrinkandroot\DoctrineBundle\Entity;

use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

trait UpdatedDatetimeEntityTrait
{
    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: false)]
    private DateTime $updated;

    public function getUpdated(): DateTime
    {
        return $this->updated;
    }
}
