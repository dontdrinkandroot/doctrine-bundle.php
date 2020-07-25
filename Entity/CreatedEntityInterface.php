<?php

namespace Dontdrinkandroot\DoctrineBundle\Entity;

use DateTimeInterface;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
interface CreatedEntityInterface
{
    public function getCreated(): ?DateTimeInterface;

    public function setCreated(DateTimeInterface $created);
}
