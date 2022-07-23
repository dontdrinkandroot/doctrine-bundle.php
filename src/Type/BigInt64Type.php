<?php

namespace Dontdrinkandroot\DoctrineBundle\Type;

use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\BigIntType;

/**
 * Type that maps bigints on 64bit systems to real int values instead of string.
 */
class BigInt64Type extends BigIntType
{
    /**
     * {@inheritdoc}
     */
    public function getBindingType(): int
    {
        return ParameterType::INTEGER;
    }

    /**
     * {@inheritdoc}
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): mixed
    {
        return $value;
    }
}
