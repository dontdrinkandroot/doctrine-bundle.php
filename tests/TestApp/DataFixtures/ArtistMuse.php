<?php

namespace Dontdrinkandroot\DoctrineBundle\Tests\TestApp\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Dontdrinkandroot\DoctrineBundle\Tests\AbstractTestCase;
use Dontdrinkandroot\DoctrineBundle\Tests\TestApp\Entity\Artist;

class ArtistMuse extends Fixture
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager): void
    {
        $artist = new Artist('Muse');
        AbstractTestCase::setPropertyValue($artist, 'id', 1);
        $manager->persist($artist);
        $manager->flush();
        $this->addReference(self::class, $artist);
    }
}
