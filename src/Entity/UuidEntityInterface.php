<?php

namespace Dontdrinkandroot\DoctrineBundle\Entity;

use Symfony\Component\Uid\Uuid;

interface UuidEntityInterface
{
    public function getUuid(): Uuid;
}
