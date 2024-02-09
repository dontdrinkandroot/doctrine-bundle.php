<?php

namespace Dontdrinkandroot\DoctrineBundle\Type;

use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\BigIntType;
use Dontdrinkandroot\Common\Asserted;
use Dontdrinkandroot\Common\Instant;
use Override;

class InstantType extends BigIntType
{
    public const NAME = 'instant';

    #[Override]
    public function getName(): string
    {
        return self::NAME;
    }

    #[Override]
    public function getBindingType(): int
    {
        return ParameterType::INTEGER;
    }

    /**
     * @param mixed $value
     * @return Instant|null
     */
    #[Override]
    public function convertToPHPValue($value, AbstractPlatform $platform): ?Instant
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

    #[Override]
    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}
