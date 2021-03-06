<?php

namespace Dontdrinkandroot\DoctrineBundle\DependencyInjection;

use Dontdrinkandroot\DoctrineBundle\Type\MillisecondsType;
use Ramsey\Uuid\Doctrine\UuidType;
use RuntimeException;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class DdrDoctrineExtension extends Extension implements PrependExtensionInterface
{
    /**
     * {@inheritdoc}
     */
    public function prepend(ContainerBuilder $container)
    {
        $bundles = $container->getParameter('kernel.bundles');
        if (!array_key_exists('DoctrineBundle', $bundles)) {
            throw new RuntimeException('Please enable DoctrineBundle in your bundles.php');
        }

        /* Register uuid type */
        $container->prependExtensionConfig(
            'doctrine',
            [
                'dbal' => [
                    'types' => [
                        'uuid' => UuidType::class,
                        'milliseconds' => MillisecondsType::class
                    ],
                ],
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config/services'));
        $loader->load('services.yml');

        if ($config['transactional_listener']['enabled']) {
            $container->setParameter(
                'ddr_doctrine.transactional_listener.managers',
                $config['transactional_listener']['managers']
            );
            $container->setParameter(
                'ddr_doctrine.transactional_listener.rollback_codes',
                $config['transactional_listener']['rollback_codes']
            );
            $loader->load('transactional_listener.yaml');
        }
    }
}
