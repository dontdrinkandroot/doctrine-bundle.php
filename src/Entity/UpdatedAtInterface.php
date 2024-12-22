<?php

namespace Dontdrinkandroot\DoctrineBundle\Entity;

use Dontdrinkandroot\Common\Instant;

interface UpdatedAtInterface
{
    public function getUpdatedAt(): Instant;
}
