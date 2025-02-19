<?php

namespace Dontdrinkandroot\DoctrineBundle\Entity;

interface CreatedAtColumnInterface extends CreatedAtInterface
{
    public function hasCreatedAt(): bool;
}
