<?php

namespace Dontdrinkandroot\DoctrineBundle\Tests\Integration\Repository;

use Doctrine\ORM\NoResultException;
use Dontdrinkandroot\DoctrineBundle\Tests\AbstractTestCase;
use Dontdrinkandroot\DoctrineBundle\Tests\TestApp\DataFixtures\ArtistMuse;
use Dontdrinkandroot\DoctrineBundle\Tests\TestApp\Entity\Artist;
use Dontdrinkandroot\DoctrineBundle\Tests\TestApp\Repository\ArtistRepository;

class ArtistRepositoryTest extends AbstractTestCase
{
    public function testCreate(): void
    {
        $this->loadFixtures();
        $artist = new Artist('Tool');
        self::getService(ArtistRepository::class)->create($artist);

        $this->assertNotNull($artist->getId());
        $this->assertNotNull($artist->getUuid());
        $this->assertNotNull($artist->getCreated());
        $this->assertNotNull($artist->getUpdated());
        $this->assertTrue($artist->isPersisted());
    }

    public function testFindPaginatedBy(): void
    {
        $this->loadFixtures([ArtistMuse::class]);
        $artists = self::getService(ArtistRepository::class)->findPaginatedBy(1, 1,);
        $this->assertCount(1, $artists);
        $this->assertEquals('Muse', iterator_to_array($artists)[0]->name);
    }

    public function testFetch(): void
    {
        $this->loadFixtures([ArtistMuse::class]);
        $artist = self::getService(ArtistRepository::class)->fetch(1);
        self::assertEquals('Muse', $artist->name);
    }

    public function testFetchNotFound(): void
    {
        $this->loadFixtures([ArtistMuse::class]);
        $this->expectException(NoResultException::class);
        self::getService(ArtistRepository::class)->fetch(2);
    }

    public function testFindAll(): void
    {
        $this->loadFixtures([ArtistMuse::class]);
        $artists = self::getService(ArtistRepository::class)->findAll();
        self::assertCount(1, $artists);
        self::assertEquals('Muse', $artists[0]->name);
    }

    public function tstFindBy(): void
    {
        $this->loadFixtures([ArtistMuse::class]);
        $artists = self::getService(ArtistRepository::class)->findBy(['name' => 'Muse']);
        self::assertCount(1, $artists);
        self::assertEquals('Muse', $artists[0]->name);
    }

    public function testFindOneBy(): void
    {
        $this->loadFixtures([ArtistMuse::class]);
        $artist = self::getService(ArtistRepository::class)->findOneBy(['name' => 'Muse']);
        self::assertNotNull($artist);
        self::assertEquals('Muse', $artist->name);
    }

    public function testFindOneByNotFound(): void
    {
        $this->loadFixtures([ArtistMuse::class]);
        $artist = self::getService(ArtistRepository::class)->findOneBy(['name' => 'Scooter']);
        self::assertNull($artist);
    }

    public function testFetchOneBy(): void
    {
        $this->loadFixtures([ArtistMuse::class]);
        $artist = self::getService(ArtistRepository::class)->fetchOneBy(['name' => 'Muse']);
        self::assertEquals('Muse', $artist->name);
    }

    public function testFetchOneByNoResult(): void
    {
        $this->loadFixtures([ArtistMuse::class]);
        $this->expectException(NoResultException::class);
        self::getService(ArtistRepository::class)->fetchOneBy(['name' => 'Scooter']);
    }

    public function testDelete(): void
    {
        $this->loadFixtures([ArtistMuse::class]);
        $artistRepository = self::getService(ArtistRepository::class);
        $artist = $artistRepository->fetch(1);
        $artistRepository->delete($artist);
        $this->expectException(NoResultException::class);
        $artistRepository->fetch(1);
    }
}
