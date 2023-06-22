<?php

namespace Dontdrinkandroot\DoctrineBundle\Entity;

use DateTime;

interface UpdatedDatetimeInterface
{
    public function getUpdated(): DateTime;
}
