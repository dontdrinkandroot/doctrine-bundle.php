<?php

namespace Dontdrinkandroot\DoctrineBundle\Entity;

/**
 * @phpstan-require-implements CreatedAtColumnInterface
 * @deprecated Use CreatedAtColumnTrait instead.
 */
trait CreatedAtTrait
{
    use CreatedAtColumnTrait;
}
