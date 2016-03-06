<?php

namespace Dontdrinkandroot\DoctrineBundle;

use Doctrine\Bundle\DoctrineBundle\DependencyInjection\Compiler\DoctrineOrmMappingsPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class DdrDoctrineBundle extends Bundle
{

    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $ormCompilerClass = DoctrineOrmMappingsPass::class;
        if (class_exists($ormCompilerClass)) {
            $container->addCompilerPass(
                DoctrineOrmMappingsPass::createYamlMappingDriver(
                    [realpath(__DIR__ . '/Resources/config/doctrine/dontdrinkandroot') => 'Dontdrinkandroot\Entity']
                )
            );
        }
    }

}
