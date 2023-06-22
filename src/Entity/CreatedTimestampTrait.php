<?php

namespace Dontdrinkandroot\DoctrineBundle\Entity;

use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Dontdrinkandroot\Common\DateUtils;

/**
 * @psalm-require-implements CreatedTimestampInterface
 */
trait CreatedTimestampTrait
{
    #[ORM\Column(type: Types::BIGINT, nullable: false, options: ["unsigned" => true])]
    protected int $created;

    public function getCreated(): int
    {
        return $this->created;
    }

    public function getCreatedDateTime(): DateTimeInterface
    {
        return DateUtils::fromMillis($this->created);
    }
}
