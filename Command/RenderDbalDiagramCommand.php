<?php


namespace Dontdrinkandroot\DoctrineBundle\Command;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Schema\Visitor\Graphviz;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RenderDbalDiagramCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('ddr:doctrine:render-dbal-diagram')
            ->setDescription('Renders an entity relationship diagram based on the current database schema');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $connectionName = $this->getContainer()->get('doctrine')->getDefaultConnectionName();
        /** @var Connection $connection */
        $connection = $this->getContainer()->get('doctrine')->getConnection($connectionName);

        $schemaManager = $connection->getSchemaManager();
        $schema = $schemaManager->createSchema();

        $graphvizVisitor = new Graphviz();
        $schema->visit($graphvizVisitor);
        $output->writeln($graphvizVisitor->getOutput());
    }
}
