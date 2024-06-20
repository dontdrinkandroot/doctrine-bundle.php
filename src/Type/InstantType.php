<?php

namespace Dontdrinkandroot\DoctrineBundle\Type;

use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Dontdrinkandroot\Common\Asserted;
use Dontdrinkandroot\Common\Instant;
use Override;

class InstantType extends Type
{
    public const string NAME = 'instant';

    #[Override]
    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getBigIntTypeDeclarationSQL($column);
    }

    #[Override]
    public function getBindingType(): ParameterType
    {
        return ParameterType::INTEGER;
    }

    #[Override]
    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): ?Instant
    {
        if (null === $value) {
            return null;
        }

        return Instant::fromTimestamp(Asserted::int($value));
    }

    #[Override]
    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?int
    {
        if (null === $value) {
            return null;
        }

        return Asserted::instanceOf($value, Instant::class)->getTimestamp();
    }
}
