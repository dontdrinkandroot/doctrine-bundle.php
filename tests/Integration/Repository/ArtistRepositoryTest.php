<?php

namespace Dontdrinkandroot\DoctrineBundle\Tests\Integration\Repository;

use Doctrine\ORM\NoResultException;
use Dontdrinkandroot\DoctrineBundle\Tests\AbstractTestCase;
use Dontdrinkandroot\DoctrineBundle\Tests\TestApp\DataFixtures\ArtistMuse;
use Dontdrinkandroot\DoctrineBundle\Tests\TestApp\DataFixtures\ArtistTool;
use Dontdrinkandroot\DoctrineBundle\Tests\TestApp\Entity\Artist;
use Dontdrinkandroot\DoctrineBundle\Tests\TestApp\Repository\ArtistRepository;

class ArtistRepositoryTest extends AbstractTestCase
{
    public function testCreate(): void
    {
        self::loadFixtures();
        $artist = new Artist('Tool');
        self::getService(ArtistRepository::class)->create($artist);

        self::assertTrue($artist->isPersisted());
        self::assertTrue($artist->hasUuid());
        self::assertTrue($artist->hasCreatedAt());
        self::assertTrue($artist->hasUpdatedAt());
    }

    public function testFindPaginatedBy(): void
    {
        self::loadFixtures([ArtistMuse::class, ArtistTool::class]);
        $artists = self::getService(ArtistRepository::class)->findPaginatedBy(
            1,
            1,
            ['name' => 'Muse'],
            ['name' => 'ASC']
        );
        self::assertCount(1, $artists);
        self::assertEquals('Muse', iterator_to_array($artists)[0]->name);
    }

    public function testFetch(): void
    {
        self::loadFixtures([ArtistMuse::class]);
        $artist = self::getService(ArtistRepository::class)->fetch(1);
        self::assertEquals('Muse', $artist->name);
    }

    public function testFetchNotFound(): void
    {
        self::loadFixtures([ArtistMuse::class]);
        $this->expectException(NoResultException::class);
        self::getService(ArtistRepository::class)->fetch(2);
    }

    public function testFindAll(): void
    {
        self::loadFixtures([ArtistMuse::class]);
        $artists = self::getService(ArtistRepository::class)->findAll();
        self::assertCount(1, $artists);
        self::assertEquals('Muse', $artists[0]->name);
    }

    public function tstFindBy(): void
    {
        self::loadFixtures([ArtistMuse::class]);
        $artists = self::getService(ArtistRepository::class)->findBy(['name' => 'Muse']);
        self::assertCount(1, $artists);
        self::assertEquals('Muse', $artists[0]->name);
    }

    public function testFindOneBy(): void
    {
        self::loadFixtures([ArtistMuse::class]);
        $artist = self::getService(ArtistRepository::class)->findOneBy(['name' => 'Muse']);
        self::assertNotNull($artist);
        self::assertEquals('Muse', $artist->name);
    }

    public function testFindOneByNotFound(): void
    {
        self::loadFixtures([ArtistMuse::class]);
        $artist = self::getService(ArtistRepository::class)->findOneBy(['name' => 'Scooter']);
        self::assertNull($artist);
    }

    public function testFetchOneBy(): void
    {
        self::loadFixtures([ArtistMuse::class]);
        $artist = self::getService(ArtistRepository::class)->fetchOneBy(['name' => 'Muse']);
        self::assertEquals('Muse', $artist->name);
    }

    public function testFetchOneByNoResult(): void
    {
        self::loadFixtures([ArtistMuse::class]);
        $this->expectException(NoResultException::class);
        self::getService(ArtistRepository::class)->fetchOneBy(['name' => 'Scooter']);
    }

    public function testDelete(): void
    {
        self::loadFixtures([ArtistMuse::class]);
        $artistRepository = self::getService(ArtistRepository::class);
        $artist = $artistRepository->fetch(1);
        $artistRepository->delete($artist);
        $this->expectException(NoResultException::class);
        $artistRepository->fetch(1);
    }
}
