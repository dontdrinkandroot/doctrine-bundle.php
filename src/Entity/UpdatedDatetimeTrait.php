<?php

namespace Dontdrinkandroot\DoctrineBundle\Entity;

use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use LogicException;
use Override;

/**
 * @psalm-require-implements UpdatedDatetimeInterface
 */
trait UpdatedDatetimeTrait
{
    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: false)]
    protected ?DateTime $updated = null;

    #[Override]
    public function getUpdated(): DateTime
    {
        return $this->updated ?? throw new LogicException('Entity was not persisted yet');
    }

    public function hasUpdated(): bool
    {
        return null !== $this->updated;
    }
}
