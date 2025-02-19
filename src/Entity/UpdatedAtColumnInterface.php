<?php

namespace Dontdrinkandroot\DoctrineBundle\Entity;

interface UpdatedAtColumnInterface extends UpdatedAtInterface
{
    public function hasUpdatedAt(): bool;
}
