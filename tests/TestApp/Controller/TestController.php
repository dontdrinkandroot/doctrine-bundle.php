<?php

namespace Dontdrinkandroot\DoctrineBundle\Tests\TestApp\Controller;

use Dontdrinkandroot\Common\Asserted;
use Dontdrinkandroot\DoctrineBundle\Tests\TestApp\Repository\ExampleEntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TestController
{
    public function __construct(private readonly ExampleEntityRepository $exampleEntityRepository)
    {
    }

    public function __invoke(Request $request, $id): Response
    {
        $exampleEntity = $this->exampleEntityRepository->find($id) ?? throw new NotFoundHttpException();
        $exampleEntity->value = 'Updated Value';

        $failWithCode = Asserted::integerishOrNull($request->query->get('failWithCode'));
        if (null !== $failWithCode) {
            throw new HttpException($failWithCode);
        }

        $returnCode = Asserted::integerishOrNull($request->query->get('returnCode'));

        return new Response($exampleEntity->value, $returnCode ?? 200);
    }
}
