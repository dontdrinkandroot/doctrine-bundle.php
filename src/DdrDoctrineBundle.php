<?php

namespace Dontdrinkandroot\DoctrineBundle;

use Dontdrinkandroot\DoctrineBundle\DependencyInjection\CompilerPass\RegisterTypesCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

use function dirname;

class DdrDoctrineBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function getPath(): string
    {
        return dirname(__DIR__);
    }

    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new RegisterTypesCompilerPass());
    }
}
