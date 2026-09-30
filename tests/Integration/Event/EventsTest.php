<?php

namespace Dontdrinkandroot\DoctrineBundle\Tests\Integration\Event;

use Doctrine\DBAL\LockMode;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\OptimisticLockException;
use Dontdrinkandroot\DoctrineBundle\Entity\VersionedInterface;
use Dontdrinkandroot\DoctrineBundle\Tests\AbstractTestCase;
use Dontdrinkandroot\DoctrineBundle\Tests\TestApp\Entity\Album;
use Dontdrinkandroot\DoctrineBundle\Tests\TestApp\Entity\Artist;
use Dontdrinkandroot\DoctrineBundle\Tests\TestApp\Entity\Genre;
use Dontdrinkandroot\DoctrineBundle\Tests\TestApp\Repository\AlbumRepository;
use Dontdrinkandroot\DoctrineBundle\Tests\TestApp\Repository\ArtistRepository;
use Dontdrinkandroot\DoctrineBundle\Tests\TestApp\Repository\GenreRepository;

class EventsTest extends AbstractTestCase
{
    public function testArtistListeners(): void
    {
        self::loadFixtures();

        $artistRepository = self::getService(ArtistRepository::class);
        $artist = new Artist('Test Artist');
        self::assertFalse($artist->isPersisted());
        self::assertFalse($artist->hasUuid());
        self::assertFalse($artist->hasUpdatedAt());
        $artistRepository->create($artist);

        self::assertTrue($artist->isPersisted());
        self::assertTrue($artist->hasUuid());
        self::assertTrue($artist->hasUpdatedAt());
        $created = $artist->getCreatedAt();
        $updated = $artist->getUpdatedAt();

        usleep(1000);

        $artist = $artistRepository->find($artist->getId());
        self::assertNotNull($artist);
        $artist->name = 'Changed Name';
        $artistRepository->flush();
        self::assertEquals($created->getTimestamp(), $artist->getCreatedAt()->getTimestamp());
        self::assertGreaterThan($updated, $artist->getUpdatedAt());
        self::assertGreaterThan($updated->getTimestamp(), $artist->getUpdatedAt()->getTimestamp());
    }

    public function testGenreListeners(): void
    {
        self::loadFixtures();

        $genreRepository = self::getService(GenreRepository::class);
        $genre = new Genre('Test Genre');
        self::assertFalse($genre->hasUpdatedAt());
        self::assertSame(1, $genre->version);
        $genreRepository->create($genre);

        self::assertTrue($genre->hasUpdatedAt());
        self::assertSame(1, $genre->version);
        $created = $genre->getCreatedAt();
        $updated = $genre->getUpdatedAt();

        usleep(1000);

        $genre = $genreRepository->find($genre->getId());
        self::assertNotNull($genre);
        self::assertInstanceOf(VersionedInterface::class, $genre);
        self::assertSame(1, $genre->version);
        $genre->name = 'Changed Name';
        $genreRepository->flush();
        self::assertEquals($created->getTimestamp(), $genre->getCreatedAt()->getTimestamp());
        self::assertGreaterThan($updated->getTimestamp(), $genre->getUpdatedAt()->getTimestamp());
        self::assertSame(2, $genre->version);
    }

    public function testGenreVersionAutoIncrement(): void
    {
        $genreRepository = self::getService(GenreRepository::class);
        $genre = new Genre('Test Genre');
        self::assertSame(1, $genre->version);
        $genreRepository->create($genre);
        $genreId = $genre->getId();
        self::assertSame(1, $genre->version);

        $genre->name = 'Changed Name';
        $genreRepository->flush();
        self::assertSame(2, $genre->version);

        $refetched = $genreRepository->fetch($genreId);
        self::assertSame(2, $refetched->version);
    }

    public function testGenreOptimisticLocking(): void
    {
        $genreRepository = self::getService(GenreRepository::class);
        $genre = new Genre('Test Genre');
        $genreRepository->create($genre);
        $genreId = $genre->getId();

        $entityManager = self::getService(EntityManagerInterface::class);
        $tableName = $entityManager->getClassMetadata(Genre::class)->getTableName();

        /* Simulate a concurrent write bumping the version behind Doctrine's back. */
        $entityManager->getConnection()->executeStatement(
            sprintf('UPDATE %s SET version = version + 1', $tableName)
        );

        $genre->name = 'Second Update';
        $this->expectException(OptimisticLockException::class);
        $genreRepository->flush();
    }

    public function testGenreOptimisticReadLock(): void
    {
        $genreRepository = self::getService(GenreRepository::class);
        $genre = new Genre('Test Genre');
        $genreRepository->create($genre);
        $genreId = $genre->getId();
        self::assertSame(1, $genre->version);

        $fresh = $genreRepository->fetch($genreId, LockMode::OPTIMISTIC, 1);
        self::assertSame(1, $fresh->version);

        $fresh->name = 'Changed Name';
        $genreRepository->flush();
        self::assertSame(2, $fresh->version);

        $genreRepository->clear();

        $updated = $genreRepository->fetch($genreId, LockMode::OPTIMISTIC, 2);
        self::assertSame(2, $updated->version);

        $this->expectException(OptimisticLockException::class);
        $genreRepository->fetch($genreId, LockMode::OPTIMISTIC, 1);
    }

    public function testAlbumListeners(): void
    {
        $artistRepository = self::getService(ArtistRepository::class);
        $artist = new Artist('Nine Inch Nails');
        $artistRepository->create($artist);

        $albumRepository = self::getService(AlbumRepository::class);
        $album = new Album($artist, 'The Fragile');
        self::assertFalse($album->hasUpdatedAt());
        $albumRepository->create($album);

        self::assertTrue($album->isPersisted());
        self::assertTrue($album->hasUpdatedAt());
        $created = $album->getCreatedAt();
        $updated = $album->getUpdatedAt();

        usleep(1000);

        $album->title = 'The Fragile (Definitive Edition)';
        $albumRepository->flush();
        self::assertEquals($created->getTimestamp(), $album->getCreatedAt()->getTimestamp());
        self::assertGreaterThan($updated->getTimestamp(), $album->getUpdatedAt()->getTimestamp());
    }
}
