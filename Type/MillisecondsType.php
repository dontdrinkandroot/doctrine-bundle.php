<?php

namespace Dontdrinkandroot\DoctrineBundle\Type;

use Doctrine\DBAL\Types\BigIntType;

class MillisecondsType extends BigIntType
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
