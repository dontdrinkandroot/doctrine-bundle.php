<?php

namespace Dontdrinkandroot\DoctrineBundle\Tests;

use Doctrine\Common\DataFixtures\ReferenceRepository;
use Dontdrinkandroot\Common\Asserted;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AbstractTestCase extends WebTestCase
{
    protected KernelBrowser $client;
    protected ReferenceRepository $referenceRepository;

    protected function loadClientAndFixtures(array $classNames = []): ReferenceRepository
    {
        $this->client = self::createClient();
        return $this->loadFixtures($classNames);
    }

    protected function loadFixtures(array $classNames): ReferenceRepository
    {
        $databaseToolCollection = Asserted::instanceOf(
            self::getContainer()->get(DatabaseToolCollection::class),
            DatabaseToolCollection::class
        );
        $this->referenceRepository = $databaseToolCollection->get()
            ->loadFixtures($classNames)
            ->getReferenceRepository();

        return $this->referenceRepository;
    }
}
