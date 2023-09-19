<?php

namespace Dontdrinkandroot\DoctrineBundle\Entity;

use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Dontdrinkandroot\Common\DateUtils;
use LogicException;

/**
 * @psalm-require-implements CreatedTimestampInterface
 */
trait CreatedTimestampTrait
{
    #[ORM\Column(type: Types::BIGINT, nullable: false, options: ["unsigned" => true])]
    protected ?int $created = null;

    public function getCreated(): int
    {
        return $this->created ?? throw new LogicException('Entity was not persisted yet');
    }

    public function getCreatedDateTime(): DateTimeInterface
    {
        return DateUtils::fromMillis($this->getCreated());
    }

    public function hasCreated(): bool
    {
        return null !== $this->created;
    }
}
