<?php

namespace Dontdrinkandroot\DoctrineBundle\Entity;

use Dontdrinkandroot\Common\Instant;

interface CreatedAtInterface
{
    public function getCreatedAt(): Instant;
}
