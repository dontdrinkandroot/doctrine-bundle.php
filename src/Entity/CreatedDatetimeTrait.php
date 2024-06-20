<?php

namespace Dontdrinkandroot\DoctrineBundle\Entity;

use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use LogicException;
use Override;

/**
 * @psalm-require-implements CreatedDatetimeInterface
 */
trait CreatedDatetimeTrait
{
    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: false)]
    protected ?DateTime $created = null;

    #[Override]
    public function getCreated(): DateTime
    {
        return $this->created ?? throw new LogicException('Entity was not persisted yet');
    }

    public function hasCreated(): bool
    {
        return null !== $this->created;
    }
}
