<?php

namespace Dontdrinkandroot\DoctrineBundle\Tests\Integration\Command;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class RenderDbalDiagramCommandTest extends KernelTestCase
{
    public function testExecute(): void
    {
        $kernel = static::createKernel();
        $application = new Application($kernel);

        $command = $application->find('ddr:doctrine:render-dbal-diagram');
        $commandTester = new CommandTester($command);
        $commandTester->execute([]);

        $output = $commandTester->getDisplay();
        $this->assertNotEmpty($output);
    }
}
