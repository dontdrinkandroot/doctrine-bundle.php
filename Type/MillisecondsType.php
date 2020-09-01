<?php

namespace Dontdrinkandroot\DoctrineBundle\Type;

use Doctrine\DBAL\Types\BigIntType;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
class MillisecondsType extends BigIntType
{
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'milliseconds';
    }
}
