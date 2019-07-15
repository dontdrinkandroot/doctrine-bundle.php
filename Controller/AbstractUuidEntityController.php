<?php

namespace Dontdrinkandroot\DoctrineBundle\Controller;

use Dontdrinkandroot\Entity\EntityInterface;
use Dontdrinkandroot\Entity\UuidEntityInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\PropertyAccess\PropertyAccess;

abstract class AbstractUuidEntityController extends AbstractEntityController
{
    /**
     * {@inheritdoc}
     */
    protected function getViewPrefix(): string
    {
        if (null !== $this->viewPrefix) {
            return $this->viewPrefix;
        }

        return '@DdrDoctrine/UuidEntity';
    }

    /**
     * {@inheritdoc}
     */
    protected function fetchEntity($id)
    {
        $entity = $this->getDoctrine()->getRepository($this->getEntityClass())->findOneBy(
            [$this->getUuidPath() => $id]
        );
        if (null === $entity) {
            throw new NotFoundHttpException();
        }

        return $entity;
    }

    /**
     * {@inheritdoc}
     */
    protected function createPostEditResponse(Request $request, $entity): Response
    {
        /** @var UuidEntityInterface $uuidEntity */
        $uuidEntity = $entity;

        $propertyAccessor = PropertyAccess::createPropertyAccessor();

        return $this->redirectToRoute(
            $this->getDetailRoute(),
            ['id' => $propertyAccessor->getValue($entity, $this->getUuidPath())]
        );
    }

    protected function getUuidPath()
    {
        return 'uuid';
    }
}
