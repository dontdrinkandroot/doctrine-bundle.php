<?php

namespace Dontdrinkandroot\DoctrineBundle\Controller;

use Dontdrinkandroot\Entity\EntityInterface;
use Dontdrinkandroot\Entity\UuidEntityInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\PropertyAccess\PropertyAccess;

abstract class AbstractUuidEntityController extends AbstractEntityController
{
    /**
     * {@inheritdoc}
     */
    protected function fetchEntity($id)
    {
        $entity = $this->getDoctrine()->getRepository($this->getEntityClass())->findOneBy(
            [$this->getIdProperty() => $id]
        );
        if (null === $entity) {
            throw new NotFoundHttpException();
        }

        return $entity;
    }

    /**
     * {@inheritdoc}
     */
    protected function getIdProperty()
    {
        return 'uuid';
    }
}
