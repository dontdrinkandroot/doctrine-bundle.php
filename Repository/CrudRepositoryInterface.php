<?php

namespace Dontdrinkandroot\DoctrineBundle\Repository;

use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ObjectRepository;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
interface CrudRepositoryInterface extends ObjectRepository
{
    public function create($entity, bool $flush = true);

    public function delete($entity, bool $flush = true);

    public function findPaginatedBy(
        int $page = 1,
        int $perPage = 10,
        array $criteria = [],
        array $orderBy = null
    ): Paginator;
}
