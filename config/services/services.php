<?php

namespace Dontdrinkandroot\DoctrineBundle\Config;

use Dontdrinkandroot\DoctrineBundle\Command\RenderDbalDiagramCommand;
use Dontdrinkandroot\DoctrineBundle\Command\RenderOrmDiagramCommand;
use Dontdrinkandroot\DoctrineBundle\Event\Listener\CreatedUpdatedListener;
use Dontdrinkandroot\DoctrineBundle\Event\Listener\UuidListener;
use Dontdrinkandroot\DoctrineBundle\Service\TransactionManager\TransactionManagerRegistry;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return function (ContainerConfigurator $configurator): void {
    $services = $configurator->services();

    $services->set(TransactionManagerRegistry::class)
        ->args([
            service('doctrine')
        ]);

    $services->set(RenderDbalDiagramCommand::class)
        ->args([
            service('doctrine')
        ])
        ->tag('console.command', ['command' => 'ddr:doctrine:render-dbal-diagram']);

    $services->set(RenderOrmDiagramCommand::class)
        ->args([
            service('doctrine')
        ])
        ->tag('console.command', ['command' => 'ddr:doctrine:render-orm-diagram']);

    $services->set(CreatedUpdatedListener::class)
        ->tag('doctrine.event_listener', ['event' => 'prePersist'])
        ->tag('doctrine.event_listener', ['event' => 'preUpdate']);

    $services->set(UuidListener::class)
        ->args([
            service('uuid.factory')
        ])
        ->tag('doctrine.event_listener', ['event' => 'prePersist']);
};
