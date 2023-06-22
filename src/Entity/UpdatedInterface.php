<?php

namespace Dontdrinkandroot\DoctrineBundle\Entity;

use Dontdrinkandroot\Common\Instant;

interface UpdatedInterface
{
    public function getUpdated(): Instant;
}
