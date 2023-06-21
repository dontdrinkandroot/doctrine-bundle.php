<?php

namespace Dontdrinkandroot\DoctrineBundle\Entity;

use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * @psalm-require-implements CreatedDatetimeEntityInterface
 */
trait CreatedDatetimeEntityTrait
{
    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: false)]
    protected DateTime $created;

    public function getCreated(): DateTime
    {
        return $this->created;
    }
}
