<?php

namespace Dontdrinkandroot\DoctrineBundle\Entity;

use Dontdrinkandroot\Common\Instant;

interface UpdatedInstantEntityInterface
{
    public function getUpdated(): Instant;
}
