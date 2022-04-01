<?php

namespace Dontdrinkandroot\DoctrineBundle\Type;

class MillisecondsType extends BigInt64Type
{
    public const NAME = 'milliseconds';

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return self::NAME;
    }
}
