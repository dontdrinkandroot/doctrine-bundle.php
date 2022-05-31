<?php

namespace Dontdrinkandroot\DoctrineBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

use function dirname;

class DdrDoctrineBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function getPath(): string
    {
        return dirname(__DIR__);
    }
}
