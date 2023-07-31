<?php

namespace Dontdrinkandroot\DoctrineBundle\Type;

use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\BigIntType;
use Dontdrinkandroot\Common\Asserted;
use Dontdrinkandroot\Common\Instant;

class InstantType extends BigIntType
{
    public const NAME = 'instant';

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
    public function getBindingType(): int
    {
        return ParameterType::INTEGER;
    }

    /**
     * {@inheritdoc}
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): ?Instant
    {
        if (null === $value) {
            return null;
        }

        return Instant::fromTimestamp(Asserted::int($value));
    }

    /**
     * {@inheritdoc}
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?int
    {
        if (null === $value) {
            return null;
        }

        return Asserted::instanceOf($value, Instant::class)->getTimestamp();
    }

    /**
     * {@inheritdoc}
     */
    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}
