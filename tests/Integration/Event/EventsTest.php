<?php

namespace Dontdrinkandroot\DoctrineBundle\Tests\Integration\Event;

use Dontdrinkandroot\DoctrineBundle\Tests\AbstractTestCase;
use Dontdrinkandroot\DoctrineBundle\Tests\TestApp\Entity\Artist;
use Dontdrinkandroot\DoctrineBundle\Tests\TestApp\Entity\Genre;
use Dontdrinkandroot\DoctrineBundle\Tests\TestApp\Repository\ArtistRepository;
use Dontdrinkandroot\DoctrineBundle\Tests\TestApp\Repository\GenreRepository;

class EventsTest extends AbstractTestCase
{
    public function testArtistListeners(): void
    {
        $artistRepository = self::getService(ArtistRepository::class);
        $artist = new Artist('Test Artist');
        $artistRepository->create($artist);

        self::assertNotNull($artist->getId());
        self::assertNotNull($artist->getUuid());
        $created = $artist->getCreated();
        $updated = $artist->getUpdated();

        usleep(1000);

        $artist->name = 'Changed Name';
        $artistRepository->flush();
        self::assertEquals($created, $artist->getCreated());
        self::assertGreaterThan($updated, $artist->getUpdated());
    }

    public function testGenreListeners(): void
    {
        $genreRepository = self::getService(GenreRepository::class);
        $genre = new Genre('Test Genre');
        $genreRepository->create($genre);

        self::assertNotNull($genre->getId());
        $created = $genre->getCreated();
        $updated = $genre->getUpdated();

        usleep(1000);

        $genre->name = 'Changed Name';
        $genreRepository->flush();
        self::assertEquals($created, $genre->getCreated());
        self::assertGreaterThan($updated, $genre->getUpdated());
    }
}
