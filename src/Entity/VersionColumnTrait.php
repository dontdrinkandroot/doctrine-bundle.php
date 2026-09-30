<?php

namespace Dontdrinkandroot\DoctrineBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * @phpstan-require-implements VersionedInterface
 */
trait VersionColumnTrait
{
    #[ORM\Version]
    #[ORM\Column(type: Types::INTEGER, nullable: false, options: ['unsigned' => true])]
    public protected(set) int $version = 1;
}
