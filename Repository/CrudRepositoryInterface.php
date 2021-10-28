<?php

namespace Dontdrinkandroot\DoctrineBundle\Repository;

use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ObjectRepository;

/**
 * @template T
 * @implements ObjectRepository<T>
 */
interface CrudRepositoryInterface extends ObjectRepository
{
    /**
     * @param mixed $id
     * @param null  $lockMode
     * @param null  $lockVersion
     *
     * @return T
     */
    public function fetch($id, $lockMode = null, $lockVersion = null);

    /**
     * @param array<string, mixed>       $criteria
     * @param array<string, string>|null $orderBy
     *
     * @return T|null
     */
    public function fetchOneBy(array $criteria, array $orderBy = null);

    /**
     * @param T    $entity
     * @param bool $flush
     *
     * @return mixed
     */
    public function create($entity, bool $flush = true): void;

    /**
     * @param T    $entity
     * @param bool $flush
     */
    public function delete($entity, bool $flush = true): void;

    public function findPaginatedBy(
        int $page = 1,
        int $perPage = 10,
        array $criteria = [],
        array $orderBy = null
    ): Paginator;

    public function flush();
}
