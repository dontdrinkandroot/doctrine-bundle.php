<?php

namespace Dontdrinkandroot\DoctrineBundle\Command;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Schema\Visitor\Graphviz;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RenderDbalDiagramCommand extends Command
{
    protected static $defaultName = 'ddr:doctrine:render-dbal-diagram';

    private Registry $registry;

    public function __construct(Registry $registry)
    {
        parent::__construct();
        $this->registry = $registry;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        $this
            ->setDescription('Renders an entity relationship diagram based on the current database schema');
    }

    /**
     * {@inheritdoc}
     */
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

        return 0;
    }
}