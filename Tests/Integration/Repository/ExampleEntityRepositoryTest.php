<?php

namespace Dontdrinkandroot\DoctrineBundle\Tests\Integration\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\Persistence\ManagerRegistry;
use Dontdrinkandroot\DoctrineBundle\Tests\TestApp\Entity\ExampleEntity;
use Dontdrinkandroot\DoctrineBundle\Tests\TestApp\Repository\ExampleEntityRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ExampleEntityRepositoryTest extends KernelTestCase
{
    private ExampleEntityRepository $exampleEntityRepository;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        self::bootKernel();
        $managerRegistry = self::$container->get(ManagerRegistry::class);
        assert($managerRegistry instanceof ManagerRegistry);

        $entityManager = $managerRegistry->getManagerForClass(ExampleEntity::class);
        assert($entityManager instanceof EntityManagerInterface);
        $metadataFactory = $entityManager->getMetadataFactory();
        $classes = $metadataFactory->getAllMetadata();
        $schemaTool = new SchemaTool($entityManager);
        $schemaTool->dropDatabase();
        $schemaTool->createSchema($classes);

        $exampleEntityRepository = self::$container->get(ExampleEntityRepository::class);
        assert($exampleEntityRepository instanceof ExampleEntityRepository);
        $this->exampleEntityRepository = $exampleEntityRepository;
    }

    public function testCreate(): void
    {
        $exampleEntity = new ExampleEntity();
        $this->exampleEntityRepository->create($exampleEntity);

        $this->assertNotNull($exampleEntity->getId());
        $this->assertNotNull($exampleEntity->getUuid());
        $this->assertNotNull($exampleEntity->getCreated());
        $this->assertNotNull($exampleEntity->getCreatedTimestamp());
        $this->assertNotNull($exampleEntity->getUpdated());
        $this->assertNotNull($exampleEntity->getUpdatedTimestamp());
    }
}
