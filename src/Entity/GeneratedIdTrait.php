<?php

namespace Dontdrinkandroot\DoctrineBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * @psalm-require-implements EntityInterface
 */
trait GeneratedIdTrait
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column(type: Types::BIGINT, nullable: false, options: ["unsigned" => true])]
    protected int $id;

    public function getId(): int
    {
        return $this->id;
    }

    public function isPersisted(): bool
    {
        return isset($this->id);
    }
}
