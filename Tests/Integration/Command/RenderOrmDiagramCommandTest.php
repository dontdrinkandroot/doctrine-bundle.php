<?php

namespace Dontdrinkandroot\DoctrineBundle\Tests\Integration\Command;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class RenderOrmDiagramCommandTest extends KernelTestCase
{
    public function testExecute(): void
    {
        $kernel = static::createKernel();
        $application = new Application($kernel);

        $command = $application->find('ddr:doctrine:render-orm-diagram');
        $commandTester = new CommandTester($command);
        $commandTester->execute(
            [
                '--skip-rendering' => ''
            ]
        );

        $output = $commandTester->getDisplay();
        $this->assertNotEmpty($output);
    }
}
