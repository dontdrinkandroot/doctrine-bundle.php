<?php

namespace Dontdrinkandroot\DoctrineBundle\Tests\Integration\Event;

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

        self::assertNotNull($artist->getId());
        self::assertNotNull($artist->getUuid());
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
        $genreRepository->create($genre);

        self::assertNotNull($genre->getId());
        self::assertTrue($genre->hasUpdatedAt());
        $created = $genre->getCreatedAt();
        $updated = $genre->getUpdatedAt();

        usleep(1000);

        $genre = $genreRepository->find($genre->getId());
        self::assertNotNull($genre);
        $genre->name = 'Changed Name';
        $genreRepository->flush();
        self::assertEquals($created->getTimestamp(), $genre->getCreatedAt()->getTimestamp());
        self::assertGreaterThan($updated->getTimestamp(), $genre->getUpdatedAt()->getTimestamp());
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

        self::assertNotNull($album->getId());
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
