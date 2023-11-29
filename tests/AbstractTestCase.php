<?php

namespace Dontdrinkandroot\DoctrineBundle\Tests;

use Doctrine\Common\DataFixtures\ReferenceRepository;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AbstractTestCase extends WebTestCase
{
    protected static function loadFixtures(array $classNames = []): ReferenceRepository
    {
        return self::getService(DatabaseToolCollection::class)->get()
            ->loadFixtures($classNames)
            ->getReferenceRepository();
    }

    /**
     * @template T
     * @param class-string<T> $class
     * @return T
     */
    protected static function getService(string $class, ?string $id = null): object
    {
        if (null === $id) {
            $id = $class;
        }
        $service = self::getContainer()->get($id);
        self::assertInstanceOf($class, $service);
        return $service;
    }
}
