<?php

namespace Dontdrinkandroot\DoctrineBundle\Tests\Integration;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TestKernelTest extends WebTestCase
{
    public function testIsWorking()
    {
        self::bootKernel();
        $this->assertNotNull(self::$kernel);
    }
}
