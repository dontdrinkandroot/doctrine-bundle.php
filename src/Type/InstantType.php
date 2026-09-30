<?php

namespace Dontdrinkandroot\DoctrineBundle\Type;

use DateTimeImmutable;
use DateTimeZone;
use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Platforms\PostgreSQLPlatform;
use Doctrine\DBAL\Types\Type;
use Dontdrinkandroot\Common\Asserted;
use Dontdrinkandroot\Common\Instant;
use Override;
use RuntimeException;

/**
 * Maps {@link Instant} to an epoch-millis BIGINT column by default, mimicking a Java
 * {@code long} holding {@code System.currentTimeMillis()}.
 *
 * On PostgreSQL the column is <code>TIMESTAMP(6) WITH TIME ZONE</code> instead, mimicking
 * Hibernate's default mapping of {@code java.time.Instant} to {@code timestamptz}
 * ({@code TIMESTAMP_UTC}), while the PHP representation remains epoch millis.
 *
 * <strong>BC note:</strong> existing BIGINT columns are not migrated automatically. Doctrine
 * cannot diff this conversion; migrate manually with
 * <code>ALTER TABLE ... ALTER COLUMN ... TYPE timestamptz USING to_timestamp(column / 1000.0)</code>.
 */
class InstantType extends Type
{
    public const string NAME = 'instant';

    private const string DATABASE_FORMAT = 'Y-m-d H:i:s.vP';

    #[Override]
    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        if ($platform instanceof PostgreSQLPlatform) {
            $precision = 6;
            if (isset($column['precision']) && is_int($column['precision'])) {
                $precision = $column['precision'];
            }

            return sprintf('TIMESTAMP(%d) WITH TIME ZONE', $precision);
        }

        return $platform->getBigIntTypeDeclarationSQL($column);
    }

    #[Override]
    public function getBindingType(): ParameterType
    {
        return ParameterType::STRING;
    }

    #[Override]
    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): ?Instant
    {
        if (null === $value) {
            return null;
        }

        if (is_string($value)) {
            return $this->fromDatabaseString($value);
        }

        return Instant::fromTimestamp(Asserted::int($value));
    }

    #[Override]
    public function convertToDatabaseValue($value, AbstractPlatform $platform): int|string|null
    {
        if (null === $value) {
            return null;
        }

        $instant = Asserted::instanceOf($value, Instant::class);

        if ($platform instanceof PostgreSQLPlatform) {
            $dateTime = DateTimeImmutable::createFromInterface($instant->getDateTime());

            return $dateTime->setTimezone(new DateTimeZone('UTC'))->format(self::DATABASE_FORMAT);
        }

        return $instant->getTimestamp();
    }

    /**
     * Parses PostgreSQL timestamp output, e.g. "2024-05-31 12:33:01.123+00",
     * "2024-05-31 12:33:01.123+00:00", "2024-05-31 12:33:01.123456+00",
     * "2024-05-31 12:33:01+00" or values shifted by the session timezone, e.g.
     * "2024-05-31 14:33:01.123+02". Milliseconds are derived from the fractional part.
     */
    private function fromDatabaseString(string $value): Instant
    {
        $pattern = '/^(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2})(?:\.(\d+))?(Z|z|[+-]\d{2}(?::?\d{2})?)?$/';
        if (1 !== preg_match($pattern, $value, $matches)) {
            throw new RuntimeException(sprintf('Unsupported timestamp format: "%s"', $value));
        }

        $offsetSeconds = 0;
        $offsetString = $matches[8] ?? '';
        if ('' !== $offsetString && 'Z' !== $offsetString && 'z' !== $offsetString) {
            $sign = '-' === $offsetString[0] ? -1 : 1;
            $digits = ltrim($offsetString, '+-');
            [$offsetHours, $offsetMinutes] = array_pad(explode(':', $digits, 2), 2, '0');
            $offsetSeconds = $sign * (((int)$offsetHours * 3600) + ((int)$offsetMinutes * 60));
        }

        $seconds = gmmktime(
            (int)$matches[4],
            (int)$matches[5],
            (int)$matches[6],
            (int)$matches[2],
            (int)$matches[3],
            (int)$matches[1]
        );
        if (false === $seconds) {
            throw new RuntimeException(sprintf('Invalid timestamp: "%s"', $value));
        }

        $millis = (int)substr(str_pad($matches[7] ?? '', 3, '0'), 0, 3);

        return Instant::fromTimestamp((($seconds - $offsetSeconds) * 1000) + $millis);
    }
}
