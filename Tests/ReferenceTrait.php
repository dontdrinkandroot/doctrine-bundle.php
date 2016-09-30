<?php

namespace Dontdrinkandroot\DoctrineBundle\Tests;

trait ReferenceTrait
{
    /**
     * @param string $name
     *
     * @return mixed
     */
    abstract protected function getReference($name);
}
