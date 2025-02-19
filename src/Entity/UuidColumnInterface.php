<?php

namespace Dontdrinkandroot\DoctrineBundle\Entity;

interface UuidColumnInterface extends UuidIdentifiedInterface
{
    public function hasUuid(): bool;
}
