<?php

namespace Dontdrinkandroot\DoctrineBundle\Entity;

use Dontdrinkandroot\Common\Instant;

interface CreatedInterface
{
    public function getCreated(): Instant;
}
