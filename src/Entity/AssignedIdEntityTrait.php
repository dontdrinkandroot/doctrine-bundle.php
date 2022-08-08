<?php

namespace Dontdrinkandroot\DoctrineBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

trait AssignedIdEntityTrait
{
    #[ORM\Id]
    #[ORM\Column(type: Types::BIGINT, nullable: false, options: ["unsigned" => true])]
    public int $id;

    public function getId(): int
    {
        return $this->id;
    }
}