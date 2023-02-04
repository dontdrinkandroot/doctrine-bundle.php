<?php

namespace Dontdrinkandroot\DoctrineBundle\Tests\TestApp\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Dontdrinkandroot\DoctrineBundle\Tests\TestApp\Entity\ExampleEntity;

class ExampleEntityOne extends Fixture
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager): void
    {
        $exampleEntity = new ExampleEntity();
        $manager->persist($exampleEntity);
        $manager->flush($exampleEntity);
        $this->addReference(self::class, $exampleEntity);
    }
}
