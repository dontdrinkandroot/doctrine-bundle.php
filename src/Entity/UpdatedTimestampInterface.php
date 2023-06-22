<?php

namespace Dontdrinkandroot\DoctrineBundle\Entity;

interface UpdatedTimestampInterface
{
    public function getUpdated(): int;
}
