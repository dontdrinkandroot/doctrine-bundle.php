<?php

namespace Dontdrinkandroot\DoctrineBundle\Tests\Unit\Entity;

use Dontdrinkandroot\DoctrineBundle\Entity\UuidEntity;
use Dontdrinkandroot\DoctrineBundle\Tests\TestApp\Entity\ExampleEntity;
use PHPUnit\Framework\TestCase;

class DefaultEntityTest extends TestCase
{
    public function testEquals(): void
    {
        $thisEntity = new ExampleEntity();
        $this->assertFalse($thisEntity->equals(null));
        $this->assertFalse($thisEntity->equals('somestring'));
        $this->assertFalse($thisEntity->equals(new UuidEntity()));

        $otherEntity = new ExampleEntity();
        $this->assertTrue($thisEntity->equals($otherEntity));
        $thisEntity->setId(1);
        $this->assertFalse($thisEntity->equals($otherEntity));
        $otherEntity->setId(2);
        $this->assertFalse($thisEntity->equals($otherEntity));
        $otherEntity->setId(1);
        $this->assertTrue($thisEntity->equals($otherEntity));
    }
}
