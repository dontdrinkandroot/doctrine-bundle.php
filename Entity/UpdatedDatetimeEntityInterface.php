<?php

namespace Dontdrinkandroot\DoctrineBundle\Entity;

use DateTime;

interface UpdatedDatetimeEntityInterface
{
    public function getUpdated(): ?DateTime;
}
