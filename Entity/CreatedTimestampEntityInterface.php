<?php

namespace Dontdrinkandroot\DoctrineBundle\Entity;

interface CreatedTimestampEntityInterface
{
    public function getCreatedTimestamp(): ?int;

    public function setCreatedTimestamp(int $timestamp): static;
}
