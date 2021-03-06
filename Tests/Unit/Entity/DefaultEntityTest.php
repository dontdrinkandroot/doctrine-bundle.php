<?php

namespace Dontdrinkandroot\DoctrineBundle\Tests\Unit\Entity;

use Dontdrinkandroot\DoctrineBundle\Entity\DefaultUuidEntity;
use Dontdrinkandroot\DoctrineBundle\Tests\TestApp\Entity\ExampleEntity;
use PHPUnit\Framework\TestCase;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
class DefaultEntityTest extends TestCase
{
    public function testEquals()
    {
        $thisEntity = new ExampleEntity();
        $this->assertFalse($thisEntity->equals(null));
        $this->assertFalse($thisEntity->equals('somestring'));
        $this->assertFalse($thisEntity->equals(new DefaultUuidEntity()));

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
