<?php

namespace Dontdrinkandroot\DoctrineBundle\Command;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Mapping\ManyToManyAssociationMapping;
use Doctrine\ORM\Mapping\ManyToOneAssociationMapping;
use Doctrine\ORM\Mapping\OneToOneOwningSideMapping;
use Doctrine\ORM\Mapping\ToOneOwningSideMapping;
use Dontdrinkandroot\Common\Asserted;
use Exception;
use Fhaculty\Graph\Graph;
use Fhaculty\Graph\Vertex;
use Graphp\GraphViz\GraphViz;
use Override;
use RuntimeException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand('ddr:doctrine:render-orm-diagram', 'Renders an entity relationship diagram based on the ORM Metadata')]
class RenderOrmDiagramCommand extends Command
{
    public function __construct(
        private readonly Registry $registry
    ) {
        parent::__construct();
    }

    #[Override]
    protected function configure(): void
    {
        $this->addOption('em', null, InputOption::VALUE_OPTIONAL, 'The entity manager to use for this command')
            ->addOption(
                'executable',
                null,
                InputOption::VALUE_REQUIRED,
                'The graphviz executable to use (default \'dot\')',
                'dot'
            )
            ->addOption('format', null, InputOption::VALUE_REQUIRED, 'The output format (png, svg, ...)', 'svg')
            ->addOption(
                'ignore-tables',
                null,
                InputOption::VALUE_REQUIRED,
                'Comma separated list of table names to ignore'
            )
            ->addOption('hide-fields', null, InputOption::VALUE_NONE, 'Do not show the fields of the entities')
            ->addOption('skip-rendering', null, InputOption::VALUE_NONE, 'Only output the source');
    }

    #[Override]
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if (!class_exists(Graph::class)) {
            throw new Exception('You need to install graphp/graphviz in order to render diagrams');
        }

        $ignoreTableNames = $this->getIgnoreTableNames($input);

        $em = $this->registry->getManager($input->getOption('em'));
        Asserted::instanceOf($em, EntityManagerInterface::class);

        $mappingDriver = Asserted::notNull($em->getConfiguration()->getMetadataDriverImpl());
        $entityClassNames = $mappingDriver->getAllClassNames();

        $graph = new Graph();
        $graph->setAttribute('graphviz.graph.overlap', 'false');
        $graph->setAttribute('graphviz.graph.splines', 'ortho');

        $vertices = [];
        /** @var array<string, string> $classToTableNames */
        $classToTableNames = [];

        /* Collect vertices */
        foreach ($entityClassNames as $entityClassName) {
            $classMetaData = $em->getClassMetadata($entityClassName);
            if (!$classMetaData->isMappedSuperclass) {
                $tableName = $classMetaData->getTableName();
                if ($output->isVerbose()) {
                    $output->writeln(sprintf('Adding entity %s with table name %s', $entityClassName, $tableName));
                }
                if (!in_array($tableName, $ignoreTableNames, true)) {
                    $vertex = $graph->createVertex();
                    $vertex->setAttribute('graphviz.shape', 'none');
                    $vertex->setAttribute(
                        'graphviz.label',
                        GraphViz::raw(
                            $this->generateVertexLabel($classMetaData, (bool)$input->getOption('hide-fields'))
                        )
                    );
                    $vertices[$tableName] = $vertex;
                }
                $classToTableNames[$classMetaData->getName()] = $tableName;
            }
        }

        /* Collect associations */
        foreach ($entityClassNames as $entityClassName) {
            $classMetaData = $em->getClassMetadata($entityClassName);
            if (!$classMetaData->isMappedSuperclass) {
                $associationMappings = $classMetaData->getAssociationMappings();
                foreach ($associationMappings as $associationMapping) {
                    if ($associationMapping->isOwningSide()) {
                        $sourceTableName = $classMetaData->getTableName();
                        $targetTableName = $classToTableNames[$associationMapping->targetEntity];

                        if (
                            in_array($sourceTableName, $ignoreTableNames)
                            || in_array($targetTableName, $ignoreTableNames)
                        ) {
                            continue;
                        }

                        /** @var Vertex $sourceVertex */
                        $sourceVertex = $vertices[$sourceTableName];
                        /** @var Vertex $targetVertex */
                        $targetVertex = $vertices[$targetTableName];
                        switch (true) {
                            case $associationMapping instanceof ManyToManyAssociationMapping:
                                $edge = $sourceVertex->createEdge($targetVertex);
                                $edge->setAttribute('graphviz.headlabel', '*');
                                $edge->setAttribute('graphviz.taillabel', '*');
                                break;
                            case $associationMapping instanceof ManyToOneAssociationMapping:
                                $edge = $sourceVertex->createEdgeTo($targetVertex);
                                if ($this->isNullableAssociation($associationMapping)) {
                                    $edge->setAttribute('graphviz.headlabel', '0,1');
                                } else {
                                    $edge->setAttribute('graphviz.headlabel', '1');
                                }
                                $edge->setAttribute('graphviz.taillabel', '*');
                                $edge->setAttribute('graphviz.arrowhead', 'none');
                                break;
                            case $associationMapping instanceof OneToOneOwningSideMapping:
                                $edge = $sourceVertex->createEdge($targetVertex);
                                if ($this->isNullableAssociation($associationMapping)) {
                                    $edge->setAttribute('graphviz.headlabel', '0,1');
                                    $edge->setAttribute('graphviz.taillabel', '0,1');
                                } else {
                                    $edge->setAttribute('graphviz.headlabel', '1');
                                    $edge->setAttribute('graphviz.taillabel', '1');
                                }
                                break;
                            default:
                                throw new RuntimeException(
                                    'Unhandled Association Type: ' . $associationMapping['type']
                                );
                        }
                    }
                }
            }
        }

        $graphviz = new GraphViz();
        $output->writeln($graphviz->createScript($graph));

        if (false === $input->getOption('skip-rendering')) {
            $graphviz->setFormat($input->getOption('format'));
            $graphviz->setExecutable($input->getOption('executable'));
            $graphviz->display($graph);
        }

        return Command::SUCCESS;
    }

    private function isNullableAssociation(ToOneOwningSideMapping $associationMapping): bool
    {
        $joinColumns = $associationMapping->joinColumns;
        if (count($joinColumns) > 1) {
            throw new Exception('More than one join Column currently not supported');
        }

        return (true === $joinColumns[0]->nullable);
    }

    /**
     * @template T of object
     * @param ClassMetadata<T> $classMetaData
     */
    private function generateVertexLabel(ClassMetadata $classMetaData, bool $hideFields = false): string
    {
        $label = '<<table cellspacing="0" border="1" cellborder="0">';
        $label .= '<tr><td bgcolor="#dedede" colspan="4"><b>' . $classMetaData->getTableName() . '</b></td></tr>';

        if (!$hideFields) {
            $fieldNames = $classMetaData->fieldNames;
            $idFieldNames = $classMetaData->getIdentifierFieldNames();
            foreach ($fieldNames as $fieldName) {
                $fieldMapping = $classMetaData->getFieldMapping($fieldName);
                $label .= '<tr>';
                $columnName = $fieldMapping['columnName'] ?? null;
                assert(null !== $columnName);
                if (in_array($fieldName, $idFieldNames)) {
                    $label .= '<td align="left"><u>' . $columnName . '</u></td>';
                } else {
                    $label .= '<td align="left">' . $columnName . '</td>';
                }
                $label .= '<td align="left">' . $fieldMapping['type'] . '</td>';
                $label .= '<td align="left">' . (true === $fieldMapping->nullable ? '' : 'notnull') . '</td>';
                $label .= '<td align="left">' . (true === $fieldMapping->unique ? 'unique' : '') . '</td>';
                $label .= '</tr>';
            }
        }

        $label .= '</table>>';

        return $label;
    }

    /** @return list<string> */
    private function getIgnoreTableNames(InputInterface $input): array
    {
        $ignoreTablesInputOption = $input->getOption('ignore-tables');
        if (null === $ignoreTablesInputOption) {
            return [];
        }

        return explode(',', (string)$ignoreTablesInputOption);
    }
}
