<?php

namespace Dontdrinkandroot\DoctrineBundle\Tests\Unit\Listener\Doctrine;

use Dontdrinkandroot\DoctrineBundle\Event\Listener\UuidEntityListener;
use Dontdrinkandroot\Entity\UuidEntityInterface;
use PHPUnit_Framework_TestCase;

class UuidEntityListenerTest extends PHPUnit_Framework_TestCase
{
    public function testGenerateRandomUuid()
    {
        $uuidEntityListener = new UuidEntityListener();
        for ($i = 0; $i < 10; $i++) {
            $uuid = $uuidEntityListener->generateRandomUuid();
            $this->assertRegExp('/' . UuidEntityInterface::VALID_UUID_PATTERN . '/', $uuid);
            $this->assertEquals('4', $uuid[14]);
            $this->assertContains($uuid[19], ['8', '9', 'a', 'b']);
        }
    }
}
