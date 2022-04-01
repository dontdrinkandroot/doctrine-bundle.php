<?php

namespace Dontdrinkandroot\DoctrineBundle\Type;

use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\PhpIntegerMappingType;
use Doctrine\DBAL\Types\Type;

/**
 * Type that maps bigints on 64bit systems to real int values instead of string.
 */
class BigInt64Type extends Type implements PhpIntegerMappingType
{
    public const NAME = 'bigint64';

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return self::NAME;
    }

    /**
     * {@inheritdoc}
     */
    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getBigIntTypeDeclarationSQL($column);
    }

    /**
     * {@inheritdoc}
     */
    public function getBindingType(): int
    {
        return ParameterType::INTEGER;
    }
}
