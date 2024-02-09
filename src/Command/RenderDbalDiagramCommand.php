<?php

namespace Dontdrinkandroot\DoctrineBundle\Command;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Schema\Visitor\Graphviz;
use Override;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand('ddr:doctrine:render-dbal-diagram', 'Renders an entity relationship diagram based on the current database schema')]
class RenderDbalDiagramCommand extends Command
{
    public function __construct(private readonly Registry $registry)
    {
        parent::__construct();
    }

    #[Override]
    protected function configure(): void
    {
    }

    #[Override]
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $connectionName = $this->registry->getDefaultConnectionName();
        /** @var Connection $connection */
        $connection = $this->registry->getConnection($connectionName);

        $schemaManager = $connection->createSchemaManager();
        $schema = $schemaManager->createSchema();

        $graphvizVisitor = new Graphviz();
        $schema->visit($graphvizVisitor);
        $output->writeln($graphvizVisitor->getOutput());

        return Command::SUCCESS;
    }
}
