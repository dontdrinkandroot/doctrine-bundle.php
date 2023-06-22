<?php

namespace Dontdrinkandroot\DoctrineBundle\Entity;

use DateTime;

interface CreatedDatetimeInterface
{
    public function getCreated(): DateTime;
}
