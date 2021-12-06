<?php

namespace Dontdrinkandroot\DoctrineBundle\Entity;

interface UpdatedTimestampEntityInterface
{
    public function getUpdatedTimestamp(): ?int;

    public function setUpdatedTimestamp(int $timestamp): static;
}
