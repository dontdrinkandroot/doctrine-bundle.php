<?php

namespace Dontdrinkandroot\DoctrineBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use LogicException;

/**
 * @psalm-require-implements EntityInterface
 */
trait GeneratedIdTrait
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column(type: Types::BIGINT, nullable: false, options: ["unsigned" => true])]
    protected ?int $id = null;

    public function getId(): int
    {
        return $this->id ?? throw new LogicException('Entity was not persisted yet');
    }

    public function isPersisted(): bool
    {
        return null !== $this->id;
    }
}
