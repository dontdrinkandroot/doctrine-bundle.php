<?php

namespace Dontdrinkandroot\DoctrineBundle\Tests\Integration\Service;

use Dontdrinkandroot\DoctrineBundle\Tests\AbstractTestCase;
use Dontdrinkandroot\DoctrineBundle\Tests\TestApp\DataFixtures\ArtistMuse;
use Dontdrinkandroot\DoctrineBundle\Tests\TestApp\Repository\ArtistRepository;

class TransactionalKernelEventListenerTest extends AbstractTestCase
{
    public function testCommit(): void
    {
        $client = self::createClient();
        self::loadFixtures([ArtistMuse::class]);
        $client->request('GET', '/test/1');
        self::assertResponseStatusCodeSame(200);
        self::assertEquals('Updated Value', $client->getResponse()->getContent());

        $artistRepository = self::getService(ArtistRepository::class);
        $artist = $artistRepository->find(1);
        self::assertNotNull($artist);
        self::assertEquals('Updated Value', $artist->name);
    }

    public function testRollbackWithException(): void
    {
        $client = self::createClient();
        self::loadFixtures([ArtistMuse::class]);
        $client->request('GET', '/test/1', ['failWithCode' => 500]);
        self::assertResponseStatusCodeSame(500);

        $artistRepository = self::getService(ArtistRepository::class);
        $artist = $artistRepository->find(1);
        self::assertNotNull($artist);
        self::assertEquals('Muse', $artist->name);
    }

    public function testRollbackWithCode(): void
    {
        $client = self::createClient();
        self::loadFixtures([ArtistMuse::class]);
        $client->request('GET', '/test/1', ['returnCode' => 500]);
        self::assertResponseStatusCodeSame(500);

        $artistRepository = self::getService(ArtistRepository::class);
        $artist = $artistRepository->find(1);
        self::assertNotNull($artist);
        self::assertEquals('Muse', $artist->name);
    }
}
