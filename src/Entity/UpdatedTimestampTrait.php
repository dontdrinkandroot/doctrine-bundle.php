<?php

namespace Dontdrinkandroot\DoctrineBundle\Entity;

use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Dontdrinkandroot\Common\DateUtils;
use LogicException;
use Override;

/**
 * @psalm-require-implements UpdatedTimestampInterface
 */
trait UpdatedTimestampTrait
{
    #[ORM\Column(type: Types::BIGINT, nullable: false, options: ["unsigned" => true])]
    protected ?int $updated = null;

    #[Override]
    public function getUpdated(): int
    {
        return $this->updated ?? throw new LogicException('Entity was not persisted yet');
    }

    public function getUpdatedDateTime(): DateTimeInterface
    {
        return DateUtils::fromMillis($this->getUpdated());
    }

    public function hasUpdated(): bool
    {
        return null !== $this->updated;
    }
}
