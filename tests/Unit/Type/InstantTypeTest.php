<?php

namespace Dontdrinkandroot\DoctrineBundle\Tests\Unit\Type;

use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\Platforms\PostgreSQLPlatform;
use Doctrine\DBAL\Platforms\SQLitePlatform;
use Dontdrinkandroot\Common\Instant;
use Dontdrinkandroot\DoctrineBundle\Type\InstantType;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class InstantTypeTest extends TestCase
{
    private const int TIMESTAMP_WITH_MILLIS = 1717158781123; // 2024-05-31 12:33:01.123 UTC

    public function testGetBindingType(): void
    {
        self::assertSame(ParameterType::STRING, (new InstantType())->getBindingType());
    }

    public function testPostgresqlDeclaration(): void
    {
        self::assertSame(
            'TIMESTAMP(6) WITH TIME ZONE',
            (new InstantType())->getSQLDeclaration([], new PostgreSQLPlatform())
        );
    }

    public function testPostgresqlDeclarationWithExplicitPrecision(): void
    {
        self::assertSame(
            'TIMESTAMP(3) WITH TIME ZONE',
            (new InstantType())->getSQLDeclaration(['precision' => 3], new PostgreSQLPlatform())
        );
    }

    public function testSqliteDeclarationIsBigint(): void
    {
        self::assertSame('BIGINT', (new InstantType())->getSQLDeclaration([], new SQLitePlatform()));
    }

    public function testConvertToDatabaseValueOnPostgresql(): void
    {
        $instant = Instant::fromTimestamp(self::TIMESTAMP_WITH_MILLIS);
        self::assertSame(
            '2024-05-31 12:33:01.123+00:00',
            (new InstantType())->convertToDatabaseValue($instant, new PostgreSQLPlatform())
        );
    }

    public function testConvertToDatabaseValueOnSqlite(): void
    {
        $instant = Instant::fromTimestamp(self::TIMESTAMP_WITH_MILLIS);
        self::assertSame(
            self::TIMESTAMP_WITH_MILLIS,
            (new InstantType())->convertToDatabaseValue($instant, new SQLitePlatform())
        );
    }

    public function testConvertToPHPValueFromInteger(): void
    {
        self::assertEquals(
            Instant::fromTimestamp(self::TIMESTAMP_WITH_MILLIS),
            (new InstantType())->convertToPHPValue(self::TIMESTAMP_WITH_MILLIS, new PostgreSQLPlatform())
        );
    }

    public function testConvertToPHPValueFromPostgresqlOutputWithShortOffset(): void
    {
        self::assertEquals(
            Instant::fromTimestamp(self::TIMESTAMP_WITH_MILLIS),
            (new InstantType())->convertToPHPValue('2024-05-31 12:33:01.123+00', new PostgreSQLPlatform())
        );
    }

    public function testConvertToPHPValueFromPostgresqlOutputWithLongOffset(): void
    {
        self::assertEquals(
            Instant::fromTimestamp(self::TIMESTAMP_WITH_MILLIS),
            (new InstantType())->convertToPHPValue('2024-05-31 12:33:01.123+00:00', new PostgreSQLPlatform())
        );
    }

    public function testConvertToPHPValueWithoutFraction(): void
    {
        self::assertEquals(
            Instant::fromTimestamp(1717158781000),
            (new InstantType())->convertToPHPValue('2024-05-31 12:33:01+00', new PostgreSQLPlatform())
        );
    }

    public function testConvertToPHPValueWithMicroseconds(): void
    {
        self::assertEquals(
            Instant::fromTimestamp(self::TIMESTAMP_WITH_MILLIS),
            (new InstantType())->convertToPHPValue('2024-05-31 12:33:01.123456+00', new PostgreSQLPlatform())
        );
    }

    public function testConvertToPHPValueWithNonUtcOffset(): void
    {
        self::assertEquals(
            Instant::fromTimestamp(self::TIMESTAMP_WITH_MILLIS),
            (new InstantType())->convertToPHPValue('2024-05-31 14:33:01.123+02', new PostgreSQLPlatform())
        );
    }

    public function testConvertToPHPValueWithNegativeNonUtcOffset(): void
    {
        self::assertEquals(
            Instant::fromTimestamp(self::TIMESTAMP_WITH_MILLIS),
            (new InstantType())->convertToPHPValue('2024-05-31 08:33:01.123-04', new PostgreSQLPlatform())
        );
    }

    public function testRoundtripOnPostgresql(): void
    {
        $instantType = new InstantType();
        $databaseValue = $instantType->convertToDatabaseValue(
            Instant::fromTimestamp(self::TIMESTAMP_WITH_MILLIS),
            new PostgreSQLPlatform()
        );
        self::assertEquals(
            Instant::fromTimestamp(self::TIMESTAMP_WITH_MILLIS),
            $instantType->convertToPHPValue($databaseValue, new PostgreSQLPlatform())
        );
    }

    public function testNullValues(): void
    {
        $instantType = new InstantType();
        self::assertNull($instantType->convertToDatabaseValue(null, new PostgreSQLPlatform()));
        self::assertNull($instantType->convertToPHPValue(null, new PostgreSQLPlatform()));
        self::assertNull($instantType->convertToDatabaseValue(null, new SQLitePlatform()));
        self::assertNull($instantType->convertToPHPValue(null, new SQLitePlatform()));
    }

    public function testConvertToPHPValueWithUnsupportedFormat(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Unsupported timestamp format');
        (new InstantType())->convertToPHPValue('not-a-timestamp', new PostgreSQLPlatform());
    }
}