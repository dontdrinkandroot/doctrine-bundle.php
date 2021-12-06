<?php

namespace Dontdrinkandroot\DoctrineBundle\Entity;

use Ramsey\Uuid\UuidInterface;

interface UuidEntityInterface
{
    public function getUuid(): ?UuidInterface;

    public function setUuid(UuidInterface $uuid): static;
}
