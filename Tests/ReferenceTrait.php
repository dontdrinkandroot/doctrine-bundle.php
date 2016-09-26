<?php

namespace Dontdrinkandroot\DoctrineBundle\Tests;

trait ReferenceTrait
{
    /**
     * @param string $name
     * @param string $class
     *
     * @return mixed
     */
    protected function getAssertedReference($name, $class)
    {
        $reference = $this->getReference($name);
        if (!is_a($reference, $class)) {
            throw new \RuntimeException(sprintf('"%s" is not a "%s"', $name, $class));
        }

        return $reference;
    }

    /**
     * @param string $name
     *
     * @return mixed
     */
    abstract protected function getReference($name);
}
