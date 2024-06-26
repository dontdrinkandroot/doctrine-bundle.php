<?php

namespace Dontdrinkandroot\DoctrineBundle\DependencyInjection\CompilerPass;

use Dontdrinkandroot\Common\Asserted;
use Dontdrinkandroot\DoctrineBundle\Type\InstantType;
use Override;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class RegisterTypesCompilerPass implements CompilerPassInterface
{
    #[Override]
    public function process(ContainerBuilder $container): void
    {
        if (!$container->hasParameter('doctrine.dbal.connection_factory.types')) {
            return;
        }

        $types = Asserted::array($container->getParameter('doctrine.dbal.connection_factory.types'));

        $types[InstantType::NAME] = ['class' => InstantType::class, 'commented' => true];

        $container->setParameter('doctrine.dbal.connection_factory.types', $types);
    }
}
