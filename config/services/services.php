<?php

namespace Dontdrinkandroot\DoctrineBundle\Config;

use Dontdrinkandroot\DoctrineBundle\Command\RenderDbalDiagramCommand;
use Dontdrinkandroot\DoctrineBundle\Command\RenderOrmDiagramCommand;
use Dontdrinkandroot\DoctrineBundle\Service\TransactionManager\TransactionManagerRegistry;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return function (ContainerConfigurator $configurator) {
    $services = $configurator->services();

    $services->set(TransactionManagerRegistry::class, TransactionManagerRegistry::class)
        ->args([
            service('doctrine')
        ]);

    $services->set(RenderDbalDiagramCommand::class, RenderDbalDiagramCommand::class)
        ->args([
            service('doctrine')
        ])
        ->tag('console.command', ['command' => 'ddr:doctrine:render-dbal-diagram']);

    $services->set(RenderOrmDiagramCommand::class, RenderOrmDiagramCommand::class)
        ->tag('console.command', ['command' => 'ddr:doctrine:render-orm-diagram']);
};
