<?php

namespace Dontdrinkandroot\DoctrineBundle\Entity;

/**
 * @phpstan-require-implements UuidColumnInterface
 * @deprecated Use UuidColumnTrait instead.
 */
trait UuidTrait
{
    use UuidColumnTrait;
}
