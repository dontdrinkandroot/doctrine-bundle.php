<?php

namespace Dontdrinkandroot\DoctrineBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('ddr_doctrine');

        // @formatter:off
        $rootNode->children()
            ->booleanNode('wrap_request_in_transaction')->defaultFalse()
            ->end()
        ->end();
        // @formatter:on

        return $treeBuilder;
    }
}
