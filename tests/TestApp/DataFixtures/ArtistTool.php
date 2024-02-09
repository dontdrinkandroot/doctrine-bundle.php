<?php

namespace Dontdrinkandroot\DoctrineBundle\Tests\TestApp\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Dontdrinkandroot\Common\ReflectionUtils;
use Dontdrinkandroot\DoctrineBundle\Tests\TestApp\Entity\Artist;
use Override;

class ArtistTool extends Fixture
{
    #[Override]
    public function load(ObjectManager $manager): void
    {
        $artist = new Artist('Tool');
        ReflectionUtils::setPropertyValue($artist, 'id', 2);
        $manager->persist($artist);
        $manager->flush();
        $this->addReference(self::class, $artist);
    }
}
