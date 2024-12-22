<?php

namespace Dontdrinkandroot\DoctrineBundle\Entity;

use Symfony\Component\Uid\Uuid;

interface UuidIdentifiedInterface
{
    public function getUuid(): Uuid;
}
