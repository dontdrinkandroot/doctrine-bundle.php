<?php

namespace Dontdrinkandroot\DoctrineBundle\Tests\Integration\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\Persistence\ManagerRegistry;
use Dontdrinkandroot\Common\Asserted;
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
        $managerRegistry = Asserted::instanceOf(
            self::getContainer()->get(ManagerRegistry::class),
            ManagerRegistry::class
        );

        $entityManager = Asserted::instanceOf(
            $managerRegistry->getManagerForClass(ExampleEntity::class),
            EntityManagerInterface::class
        );
        $metadataFactory = $entityManager->getMetadataFactory();
        $classes = $metadataFactory->getAllMetadata();
        $schemaTool = new SchemaTool($entityManager);
        $schemaTool->dropDatabase();
        $schemaTool->createSchema($classes);

        $exampleEntityRepository = Asserted::instanceOf(
            self::getContainer()->get(ExampleEntityRepository::class),
            ExampleEntityRepository::class
        );
        $this->exampleEntityRepository = $exampleEntityRepository;
    }

    public function testCreate(): void
    {
        $exampleEntity = new ExampleEntity();
        $this->exampleEntityRepository->create($exampleEntity);

        $this->assertNotNull($exampleEntity->getId());
        $this->assertNotNull($exampleEntity->getUuid());
        $this->assertNotNull($exampleEntity->getCreated());
        $this->assertNotNull($exampleEntity->getUpdated());
        $this->assertTrue($exampleEntity->isPersisted());
    }
}
