<?php

namespace Dontdrinkandroot\DoctrineBundle\Tests\Integration\Repository;

use Doctrine\ORM\NoResultException;
use Dontdrinkandroot\Common\Asserted;
use Dontdrinkandroot\DoctrineBundle\Tests\AbstractTestCase;
use Dontdrinkandroot\DoctrineBundle\Tests\TestApp\DataFixtures\ArtistMuse;
use Dontdrinkandroot\DoctrineBundle\Tests\TestApp\Entity\Artist;
use Dontdrinkandroot\DoctrineBundle\Tests\TestApp\Repository\ArtistRepository;

class ArtistRepositoryTest extends AbstractTestCase
{
    protected function getArtistRepository(): ArtistRepository
    {
        return Asserted::instanceOf(
            self::getContainer()->get(ArtistRepository::class),
            ArtistRepository::class
        );
    }

    public function testCreate(): void
    {
        $this->loadFixtures();
        $artist = new Artist('Tool');
        $this->getArtistRepository()->create($artist);

        $this->assertNotNull($artist->getId());
        $this->assertNotNull($artist->getUuid());
        $this->assertNotNull($artist->getCreated());
        $this->assertNotNull($artist->getUpdated());
        $this->assertTrue($artist->isPersisted());
    }

    public function testFindPaginatedBy(): void
    {
        $this->loadFixtures([ArtistMuse::class]);
        $artists = $this->getArtistRepository()->findPaginatedBy(1, 1,);
        $this->assertCount(1, $artists);
        $this->assertEquals('Muse', iterator_to_array($artists)[0]->name);
    }

    public function testFetch(): void
    {
        $this->loadFixtures([ArtistMuse::class]);
        $artist = $this->getArtistRepository()->fetch(1);
        self::assertEquals('Muse', $artist->name);
    }

    public function testFetchNotFound(): void
    {
        $this->loadFixtures([ArtistMuse::class]);
        $this->expectException(NoResultException::class);
        $this->getArtistRepository()->fetch(2);
    }

    public function testFindAll(): void
    {
        $this->loadFixtures([ArtistMuse::class]);
        $artists = $this->getArtistRepository()->findAll();
        self::assertCount(1, $artists);
        self::assertEquals('Muse', $artists[0]->name);
    }

    public function tstFindBy(): void
    {
        $this->loadFixtures([ArtistMuse::class]);
        $artists = $this->getArtistRepository()->findBy(['name' => 'Muse']);
        self::assertCount(1, $artists);
        self::assertEquals('Muse', $artists[0]->name);
    }

    public function tetFindOneBy(): void
    {
        $this->loadFixtures([ArtistMuse::class]);
        $artist = $this->getArtistRepository()->findOneBy(['name' => 'Muse']);
        self::assertEquals('Muse', $artist->name);
    }

    public function testFindOneByNotFound(): void
    {
        $this->loadFixtures([ArtistMuse::class]);
        $artist = $this->getArtistRepository()->findOneBy(['name' => 'Scooter']);
        self::assertNull($artist);
    }

    public function testFetchOneBy(): void
    {
        $this->loadFixtures([ArtistMuse::class]);
        $artist = $this->getArtistRepository()->fetchOneBy(['name' => 'Muse']);
        self::assertEquals('Muse', $artist->name);
    }

    public function testFetchOneByNoResult(): void
    {
        $this->loadFixtures([ArtistMuse::class]);
        $this->expectException(NoResultException::class);
        $this->getArtistRepository()->fetchOneBy(['name' => 'Scooter']);
    }

    public function testDelete(): void
    {
        $this->loadFixtures([ArtistMuse::class]);
        $artist = $this->getArtistRepository()->fetch(1);
        $this->getArtistRepository()->delete($artist);
        $this->expectException(NoResultException::class);
        $this->getArtistRepository()->fetch(1);
    }
}
