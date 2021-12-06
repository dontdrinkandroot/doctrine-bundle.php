<?php

namespace Dontdrinkandroot\DoctrineBundle\Type;

use Doctrine\DBAL\Types\BigIntType;

class MillisecondsType extends BigIntType
{
    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return 'milliseconds';
    }
}
