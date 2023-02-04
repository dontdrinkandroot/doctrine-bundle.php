<?php

namespace Dontdrinkandroot\DoctrineBundle\Tests\Integration\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\Persistence\ManagerRegistry;
use Dontdrinkandroot\Common\Asserted;
use Dontdrinkandroot\DoctrineBundle\Tests\TestApp\Entity\Artist;
use Dontdrinkandroot\DoctrineBundle\Tests\TestApp\Repository\ArtistRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ArtistRepositoryTest extends KernelTestCase
{
    private ArtistRepository $artistRepository;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        self::bootKernel();
        $managerRegistry = Asserted::instanceOf(
            self::getContainer()->get(ManagerRegistry::class),
            ManagerRegistry::class
        );

        $entityManager = Asserted::instanceOf(
            $managerRegistry->getManagerForClass(Artist::class),
            EntityManagerInterface::class
        );
        $metadataFactory = $entityManager->getMetadataFactory();
        $classes = $metadataFactory->getAllMetadata();
        $schemaTool = new SchemaTool($entityManager);
        $schemaTool->dropDatabase();
        $schemaTool->createSchema($classes);

        $artistRepository = Asserted::instanceOf(
            self::getContainer()->get(ArtistRepository::class),
            ArtistRepository::class
        );
        $this->artistRepository = $artistRepository;
    }

    public function testCreate(): void
    {
        $artist = new Artist('Tool');
        $this->artistRepository->create($artist);

        $this->assertNotNull($artist->getId());
        $this->assertNotNull($artist->getUuid());
        $this->assertNotNull($artist->getCreated());
        $this->assertNotNull($artist->getUpdated());
        $this->assertTrue($artist->isPersisted());
    }
}
