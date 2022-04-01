<?php

namespace Dontdrinkandroot\DoctrineBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Dontdrinkandroot\DoctrineBundle\Type\BigInt64Type;

trait AssignedIdEntityTrait
{
    #[ORM\Id]
    #[ORM\Column(type: BigInt64Type::NAME, nullable: false, options: ["unsigned" => true])]
    public int $id;

    public function getId(): int
    {
        return $this->id;
    }
}
