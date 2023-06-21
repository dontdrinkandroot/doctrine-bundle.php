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
        $this->loadFixtures();

        $artistRepository = self::getService(ArtistRepository::class);
        $artist = new Artist('Test Artist');
        self::assertFalse($artist->isPersisted());
        self::assertFalse($artist->hasUuid());
        $artistRepository->create($artist);

        self::assertNotNull($artist->getId());
        self::assertNotNull($artist->getUuid());
        $created = $artist->getCreated();
        $updated = $artist->getUpdated();

        usleep(1000);

        $artist = $artistRepository->find($artist->getId());
        self::assertNotNull($artist);
        $artist->name = 'Changed Name';
        $artistRepository->flush();
        self::assertEquals($created, $artist->getCreated());
        self::assertGreaterThan($updated, $artist->getUpdated());
    }

    public function testGenreListeners(): void
    {
        $this->loadFixtures();

        $genreRepository = self::getService(GenreRepository::class);
        $genre = new Genre('Test Genre');
        $genreRepository->create($genre);

        self::assertNotNull($genre->getId());
        $created = $genre->getCreated();
        $updated = $genre->getUpdated();

        usleep(1000);

        $genre = $genreRepository->find($genre->getId());
        self::assertNotNull($genre);
        $genre->name = 'Changed Name';
        $genreRepository->flush();
        self::assertEquals($created, $genre->getCreated());
        self::assertGreaterThan($updated, $genre->getUpdated());
    }

    public function testAlbumListeners(): void
    {
        $artistRepository = self::getService(ArtistRepository::class);
        $artist = new Artist('Nine Inch Nails');
        $artistRepository->create($artist);

        $albumRepository = self::getService(AlbumRepository::class);
        $album = new Album($artist, 'The Fragile');
        $albumRepository->create($album);

        self::assertNotNull($album->getId());
        $created = $album->getCreated();
        $updated = $album->getUpdated();

        usleep(1000);

        $album->title = 'The Fragile (Definitive Edition)';
        $albumRepository->flush();
        self::assertEquals($created->getTimestamp(), $album->getCreated()->getTimestamp());
        self::assertGreaterThan($updated->getTimestamp(), $album->getUpdated()->getTimestamp());
    }
}
