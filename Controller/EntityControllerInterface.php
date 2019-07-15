<?php
namespace Dontdrinkandroot\DoctrineBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

interface EntityControllerInterface
{
    public function listAction(Request $request): Response;

    public function detailAction(Request $request, $id): Response;

    public function editAction(Request $request, $id = null): Response;

    public function deleteAction(Request $request, $id): Response;

    public function getRoutePrefix(): string;

    public function getPathPrefix(): string;
}
