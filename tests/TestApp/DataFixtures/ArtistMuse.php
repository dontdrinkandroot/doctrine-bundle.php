<?php

namespace Dontdrinkandroot\DoctrineBundle\Tests\TestApp\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Dontdrinkandroot\Common\ReflectionUtils;
use Dontdrinkandroot\DoctrineBundle\Tests\TestApp\Entity\Artist;
use Override;

class ArtistMuse extends Fixture
{
    #[Override]
    public function load(ObjectManager $manager): void
    {
        $artist = new Artist('Muse');
        ReflectionUtils::setPropertyValue($artist, 'id', 1);
        $manager->persist($artist);
        $manager->flush();
        $this->addReference(self::class, $artist);
    }
}
