<?php

namespace Dontdrinkandroot\DoctrineBundle\Type;

use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\BigIntType;
use Override;

/**
 * Type that maps bigints on 64bit systems to real int values instead of string.
 */
class BigInt64Type extends BigIntType
{
    #[Override]
    public function getBindingType(): int
    {
        return ParameterType::INTEGER;
    }

    #[Override]
    public function convertToPHPValue($value, AbstractPlatform $platform): mixed
    {
        return $value;
    }
}
