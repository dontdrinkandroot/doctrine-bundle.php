<?php

namespace Dontdrinkandroot\DoctrineBundle;

use Dontdrinkandroot\DoctrineBundle\DependencyInjection\CompilerPass\RegisterTypesCompilerPass;
use Override;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

use function dirname;

class DdrDoctrineBundle extends Bundle
{
    #[Override]
    public function getPath(): string
    {
        return dirname(__DIR__);
    }

    #[Override]
    public function build(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new RegisterTypesCompilerPass());
    }
}
