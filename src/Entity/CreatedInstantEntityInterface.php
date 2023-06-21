<?php

namespace Dontdrinkandroot\DoctrineBundle\Entity;

use Dontdrinkandroot\Common\Instant;

interface CreatedInstantEntityInterface
{
    public function getCreated(): Instant;
}
