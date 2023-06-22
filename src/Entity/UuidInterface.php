<?php

namespace Dontdrinkandroot\DoctrineBundle\Entity;

use Symfony\Component\Uid\Uuid;

interface UuidInterface
{
    public function getUuid(): Uuid;
}
