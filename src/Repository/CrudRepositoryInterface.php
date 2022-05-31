<?php

namespace Dontdrinkandroot\DoctrineBundle\Repository;

use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ObjectRepository;

/**
 * @template T of object
 * @extends ObjectRepository<T>
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
    public function fetch($id, $lockMode = null, $lockVersion = null): object;

    /**
     * @param array<string, mixed>       $criteria
     * @param array<string, string>|null $orderBy
     *
     * @return T
     */
    public function fetchOneBy(array $criteria, array $orderBy = null): object;

    /**
     * @param T    $entity
     * @param bool $flush
     */
    public function create($entity, bool $flush = true): void;

    /**
     * @param T    $entity
     * @param bool $flush
     */
    public function delete($entity, bool $flush = true): void;

    /**
     * @param int        $page
     * @param int        $perPage
     * @param array<string, mixed>       $criteria
     * @param array<string, string>|null $orderBy
     *
     * @return Paginator<T>
     */
    public function findPaginatedBy(
        int $page = 1,
        int $perPage = 10,
        array $criteria = [],
        array $orderBy = null
    ): Paginator;

    public function flush(): void;
}
