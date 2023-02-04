<?php

namespace Dontdrinkandroot\DoctrineBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class DdrDoctrineExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\PhpFileLoader($container, new FileLocator(__DIR__ . '/../../config/services'));
        $loader->load('services.php');

        if ($config['transactional_listener']['enabled']) {
            $container->setParameter(
                'ddr_doctrine.transactional_listener.managers',
                $config['transactional_listener']['managers']
            );
            $container->setParameter(
                'ddr_doctrine.transactional_listener.rollback_codes',
                $config['transactional_listener']['rollback_codes']
            );
            $loader->load('transactional_listener.php');
        }
    }
}
