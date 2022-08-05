<?php

namespace Dontdrinkandroot\DoctrineBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\HttpFoundation\Response;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('ddr_doctrine');
        $rootNode = $treeBuilder->getRootNode();

        // @formatter:off
        $rootNode->children()
            ->arrayNode('transactional_listener')
                ->canBeDisabled()
                ->addDefaultsIfNotSet()
                ->children()
                    ->arrayNode('managers')
                        ->scalarPrototype()->end()
                        ->defaultValue(['default'])
                    ->end()
                    ->arrayNode('rollback_codes')
                        ->integerPrototype()->end()
                        ->defaultValue([
                            Response::HTTP_BAD_REQUEST,
                            Response::HTTP_UNPROCESSABLE_ENTITY,
                            Response::HTTP_INTERNAL_SERVER_ERROR
                        ])
                    ->end()
                ->end()
            ->end()
        ->end();
        // @formatter:on

        return $treeBuilder;
    }
}
