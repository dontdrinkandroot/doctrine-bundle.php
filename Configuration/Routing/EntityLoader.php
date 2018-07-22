<?php

namespace Dontdrinkandroot\DoctrineBundle\Configuration\Routing;

use Dontdrinkandroot\DoctrineBundle\Controller\EntityControllerInterface;
use Symfony\Component\Config\Loader\Loader;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class EntityLoader extends Loader
{
    /**
     * @var KernelInterface
     */
    private $kernel;

    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * {@inheritdoc}
     */
    public function load($resource, $type = null)
    {
        $controllerClass = $this->resolveControllerClass($resource);

        /** @var EntityControllerInterface $controller */
        $controller = new $controllerClass;

        $routes = $this->createRouteCollection($controller, $resource);

        return $routes;
    }

    protected function resolveControllerClass($resource)
    {
        $controllerClass = null;
        if (false !== strpos($resource, ':')) {
            $controllerClass = $this->resolveControllerClassByBundle($resource);
        } else {
            $controllerClass = $resource;
        }

        $reflectionClass = new \ReflectionClass($controllerClass);
        if (!$reflectionClass->implementsInterface($this->getControllerClass())) {
            throw new \Exception('Controller must implement ' . $this->getControllerClass());
        }

        return $controllerClass;
    }

    private function resolveControllerClassByBundle($resource)
    {
        $parts = explode(':', $resource);
        if (2 !== count($parts)) {
            throw new \Exception('Can not process bundle resource string');
        }

        $bundle = $parts[0];
        $controllerName = $parts[1];

        try {
            $allBundles = $this->kernel->getBundle($bundle, false);
        } catch (\InvalidArgumentException $e) {
            throw new \Exception(sprintf('Bundle "%s" not found', $bundle));
        }

        $candidates = [];
        foreach ($allBundles as $b) {
            $candidate = $b->getNamespace() . '\\Controller\\' . $controllerName . 'Controller';
            if (class_exists($candidate)) {
                $candidates[] = $candidate;
            }

            $matchingBundles[] = $b->getName();
        }

        if (0 === count($candidates)) {
            throw new \Exception('Controller not found');
        }

        if (count($candidates) > 1) {
            throw new \Exception('More than one matching candidate found');
        }

        return $candidates[0];
    }

    /**
     * {@inheritdoc}
     */
    public function supports($resource, $type = null)
    {
        return $this->getType() === $type;
    }

    /**
     * @return string
     */
    protected function getType()
    {
        return 'ddr_entity';
    }

    /**
     * @return string
     */
    protected function getControllerClass()
    {
        return EntityControllerInterface::class;
    }

    /**
     * @param EntityControllerInterface $controller
     * @param                           $resource
     *
     * @return RouteCollection
     */
    protected function createRouteCollection(EntityControllerInterface $controller, $resource)
    {
        $routePrefix = $controller->getRoutePrefix();
        $pathPrefix = $controller->getPathPrefix();

        $routes = new RouteCollection();

        $routes->add(
            $routePrefix . '.edit',
            new Route($pathPrefix . '{id}/edit', ['_controller' => $resource . '::editAction'])
        );
        $routes->add(
            $routePrefix . '.delete',
            new Route($pathPrefix . '{id}/delete', ['_controller' => $resource . '::deleteAction'])
        );
        $routes->add(
            $routePrefix . '.detail',
            new Route($pathPrefix . '{id}', ['_controller' => $resource . '::detailAction'])
        );
        $routes->add($routePrefix . '.list', new Route($pathPrefix, ['_controller' => $resource . '::listAction']));

        return $routes;
    }
}
