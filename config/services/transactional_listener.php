<?php

namespace Dontdrinkandroot\DoctrineBundle\Config;

use Dontdrinkandroot\DoctrineBundle\Event\Listener\TransactionalKernelEventListener;
use Dontdrinkandroot\DoctrineBundle\Service\TransactionManager\TransactionManagerRegistry;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

use function Symfony\Component\DependencyInjection\Loader\Configurator\param;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return function (ContainerConfigurator $configurator): void {
    $services = $configurator->services();

    $services->set(TransactionalKernelEventListener::class, TransactionalKernelEventListener::class)
        ->args([
            service(TransactionManagerRegistry::class),
            param('ddr_doctrine.transactional_listener.managers'),
            param('ddr_doctrine.transactional_listener.rollback_codes')
        ])
        ->call('setLogger', [service('logger')])
        ->tag('kernel.event_listener', ['event' => 'kernel.request', 'priority' => 2048])
        ->tag('kernel.event_listener', ['event' => 'kernel.finish_request'])
        ->tag('kernel.event_listener', ['event' => 'kernel.terminate'])
        ->tag('kernel.event_listener', ['event' => 'kernel.exception'])
        ->tag('monolog.logger', ['channel' => 'doctrine']);
};
