<?php

namespace Dontdrinkandroot\DoctrineBundle\Entity;

use DateTimeInterface;

interface CreatedEntityInterface
{
    public function getCreated(): ?DateTimeInterface;

    public function setCreated(DateTimeInterface $created);
}
