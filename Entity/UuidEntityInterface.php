<?php

namespace Dontdrinkandroot\DoctrineBundle\Entity;

use Ramsey\Uuid\UuidInterface;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
interface UuidEntityInterface
{
    public function getUuid(): ?UuidInterface;

    public function setUuid(UuidInterface $uuid);
}
