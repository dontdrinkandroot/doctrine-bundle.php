<?php

namespace Dontdrinkandroot\DoctrineBundle\Entity;

use DateTimeInterface;

interface UpdatedEntityInterface
{
    public function getUpdated(): ?DateTimeInterface;

    public function setUpdated(DateTimeInterface $updated);
}
