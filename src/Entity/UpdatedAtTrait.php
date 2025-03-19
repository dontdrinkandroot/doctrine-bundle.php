<?php

namespace Dontdrinkandroot\DoctrineBundle\Entity;

/**
 * @phpstan-require-implements UpdatedAtColumnInterface
 * @deprecated Use UpdatedAtColumnTrait instead.
 */
trait UpdatedAtTrait
{
    use UpdatedAtColumnTrait;
}
