<?php

namespace Dontdrinkandroot\DoctrineBundle\Tests\TestApp\Controller;

use Dontdrinkandroot\Common\Asserted;
use Dontdrinkandroot\DoctrineBundle\Tests\TestApp\Repository\ArtistRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TestController
{
    public function __construct(private readonly ArtistRepository $artistRepository)
    {
    }

    public function __invoke(Request $request, int $id): Response
    {
        $artist = $this->artistRepository->find($id) ?? throw new NotFoundHttpException();
        $artist->name = 'Updated Value';

        $failWithCode = Asserted::integerishOrNull($request->query->get('failWithCode'));
        if (null !== $failWithCode) {
            throw new HttpException($failWithCode);
        }

        $returnCode = Asserted::integerishOrNull($request->query->get('returnCode'));

        return new Response($artist->name, $returnCode ?? 200);
    }
}
