<?php

namespace Dontdrinkandroot\DoctrineBundle\Entity;

/**
 * @phpstan-require-implements EntityInterface
 * @deprecated Use AssignedIdColumnTrait instead.
 */
trait AssignedIdTrait
{
    use AssignedIdColumnTrait;
}
