<?php

namespace Dontdrinkandroot\DoctrineBundle\Entity;

use DateTime;

interface CreatedDatetimeEntityInterface
{
    public function getCreated(): DateTime;
}
