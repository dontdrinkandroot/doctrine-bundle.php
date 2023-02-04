<?php

namespace Dontdrinkandroot\DoctrineBundle\Tests\TestApp\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Dontdrinkandroot\DoctrineBundle\Tests\TestApp\Entity\Artist;

class ArtistMuse extends Fixture
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager): void
    {
        $artist = new Artist('Muse');
        $artist->id = 1;
        $manager->persist($artist);
        $manager->flush($artist);
        $this->addReference(self::class, $artist);
    }
}
