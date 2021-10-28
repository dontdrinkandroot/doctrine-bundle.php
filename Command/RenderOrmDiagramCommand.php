<?php

namespace Dontdrinkandroot\DoctrineBundle\Command;

use Doctrine\Bundle\DoctrineBundle\Command\Proxy\DoctrineCommandHelper;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Mapping\ClassMetadataInfo;
use Exception;
use Fhaculty\Graph\Graph;
use Fhaculty\Graph\Vertex;
use Graphp\GraphViz\GraphViz;
use RuntimeException;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class RenderOrmDiagramCommand extends Command
{
    protected static $defaultName = 'ddr:doctrine:render-orm-diagram';

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setDescription('Renders an entity relationship diagram based on the ORM Metadata')
            ->addOption('em', null, InputOption::VALUE_OPTIONAL, 'The entity manager to use for this command')
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

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (!class_exists(Graph::class)) {
            throw new Exception('You need to install graphp/graphviz in order to render diagrams');
        }

        $ignoreTableNames = $this->getIgnoreTableNames($input);

        $application = $this->getApplication();
        assert($application instanceof Application);
        DoctrineCommandHelper::setApplicationEntityManager($application, $input->getOption('em'));
        /** @var EntityManagerInterface $em */
        $em = $this->getHelper('em')->getEntityManager();

        $mappingDriver = $em->getConfiguration()->getMetadataDriverImpl();
        assert(null !== $mappingDriver);
        $entityClassNames = $mappingDriver->getAllClassNames();

        $graph = new Graph();
        $graph->setAttribute('graphviz.graph.overlap', 'false');
        $graph->setAttribute('graphviz.graph.splines', 'ortho');

        $vertices = [];
        $metaDatas = [];
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
                    if ($associationMapping['isOwningSide']) {
                        $sourceTableName = $classMetaData->getTableName();
                        $targetTableName = $classToTableNames[$associationMapping['targetEntity']];

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
                        switch ($associationMapping['type']) {
                            case ClassMetadataInfo::MANY_TO_MANY:
                                $edge = $sourceVertex->createEdge($targetVertex);
                                $edge->setAttribute('graphviz.headlabel', '*');
                                $edge->setAttribute('graphviz.taillabel', '*');
                                break;
                            case ClassMetadataInfo::ONE_TO_MANY:
                                throw new Exception(
                                    'One to many not supported yet: ' . $sourceTableName . ':' . $associationMapping['fieldName']
                                );
                                break;
                            case ClassMetadataInfo::MANY_TO_ONE:
                                $edge = $sourceVertex->createEdgeTo($targetVertex);
                                if ($this->isNullableAssociation($associationMapping)) {
                                    $edge->setAttribute('graphviz.headlabel', '0,1');
                                } else {
                                    $edge->setAttribute('graphviz.headlabel', '1');
                                }
                                $edge->setAttribute('graphviz.taillabel', '*');
                                $edge->setAttribute('graphviz.arrowhead', 'none');
                                break;
                            case ClassMetadataInfo::ONE_TO_ONE:
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

        return 0;
    }

    private function isNullableAssociation($associationMapping)
    {
        $joinColumns = $associationMapping['joinColumns'];
        if (count($joinColumns) > 1) {
            throw new Exception('More than one join Column currently not supported');
        }
        if (!array_key_exists('nullable', $joinColumns[0])) {
            return true;
        }

        return $joinColumns[0]['nullable'];
    }

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
                $label .= '<td align="left">' . ($this->isNullable($fieldMapping) ? '' : 'notnull') . '</td>';
                $label .= '<td align="left">' . ($this->isUnique($fieldMapping) ? 'unique' : '') . '</td>';
                $label .= '</tr>';
            }
        }

        $label .= '</table>>';

        return $label;
    }

    private function getIgnoreTableNames(InputInterface $input)
    {
        $ignoreTablesInputOption = $input->getOption('ignore-tables');
        if (null === $ignoreTablesInputOption) {
            return [];
        }

        return explode(',', $ignoreTablesInputOption);
    }

    private function isNullable(array $fieldMapping): bool
    {
        if (!array_key_exists('nullable', $fieldMapping)) {
            return false;
        }

        return $fieldMapping['nullable'] === true;
    }

    private function isUnique(array $fieldMapping): bool
    {
        if (!array_key_exists('unique', $fieldMapping)) {
            return false;
        }

        return $fieldMapping['unique'] === true;
    }
}
