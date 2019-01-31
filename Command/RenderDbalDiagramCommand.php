<?php

namespace Dontdrinkandroot\DoctrineBundle\Command;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Schema\Visitor\Graphviz;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RenderDbalDiagramCommand extends Command
{
    /**
     * @var Registry
     */
    private $registry;

    public function __construct(Registry $registry)
    {
        parent::__construct();
        $this->registry = $registry;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setDescription('Renders an entity relationship diagram based on the current database schema');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $connectionName = $this->registry->getDefaultConnectionName();
        /** @var Connection $connection */
        $connection = $this->registry->getConnection($connectionName);

        $schemaManager = $connection->getSchemaManager();
        $schema = $schemaManager->createSchema();

        $graphvizVisitor = new Graphviz();
        $schema->visit($graphvizVisitor);
        $output->writeln($graphvizVisitor->getOutput());
    }
}
