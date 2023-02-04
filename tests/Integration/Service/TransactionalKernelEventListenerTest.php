<?php

namespace Dontdrinkandroot\DoctrineBundle\Tests\Integration\Service;

use Doctrine\Common\DataFixtures\ReferenceRepository;
use Dontdrinkandroot\Common\Asserted;
use Dontdrinkandroot\DoctrineBundle\Tests\TestApp\DataFixtures\ExampleEntityOne;
use Dontdrinkandroot\DoctrineBundle\Tests\TestApp\Repository\ExampleEntityRepository;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TransactionalKernelEventListenerTest extends WebTestCase
{
    private KernelBrowser $client;
    private ReferenceRepository $referenceRepository;

    protected function loadClientAndFixtures(array $classNames = []): ReferenceRepository
    {
        $this->client = self::createClient();
        $databaseToolCollection = Asserted::instanceOf(
            self::getContainer()->get(DatabaseToolCollection::class),
            DatabaseToolCollection::class
        );
        $this->referenceRepository = $databaseToolCollection->get()
            ->loadFixtures($classNames)
            ->getReferenceRepository();

        return $this->referenceRepository;
    }

    public function testCommit(): void
    {
        $this->loadClientAndFixtures([ExampleEntityOne::class]);
        $this->client->request('GET', '/test/1');
        self::assertResponseStatusCodeSame(200);
        $this->assertEquals('Updated Value', $this->client->getResponse()->getContent());

        $exampleEntityRepository = Asserted::instanceOf(
            self::getContainer()->get(ExampleEntityRepository::class),
            ExampleEntityRepository::class
        );
        $exampleEntity = $exampleEntityRepository->find(1);
        self::assertEquals('Updated Value', $exampleEntity->value);
    }

    public function testRollbackWithException(): void
    {
        $this->loadClientAndFixtures([ExampleEntityOne::class]);
        $this->client->request('GET', '/test/1', ['failWithCode' => 500]);
        self::assertResponseStatusCodeSame(500);

        $exampleEntityRepository = Asserted::instanceOf(
            self::getContainer()->get(ExampleEntityRepository::class),
            ExampleEntityRepository::class
        );
        $exampleEntity = $exampleEntityRepository->find(1);
        self::assertNull($exampleEntity->value);
    }

    public function testRollbackWithCode(): void
    {
        $this->loadClientAndFixtures([ExampleEntityOne::class]);
        $this->client->request('GET', '/test/1', ['returnCode' => 500]);
        self::assertResponseStatusCodeSame(500);

        $exampleEntityRepository = Asserted::instanceOf(
            self::getContainer()->get(ExampleEntityRepository::class),
            ExampleEntityRepository::class
        );
        $exampleEntity = $exampleEntityRepository->find(1);
        self::assertNull($exampleEntity->value);
    }
}
