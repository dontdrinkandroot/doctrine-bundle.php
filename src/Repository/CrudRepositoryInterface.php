<?php

namespace Dontdrinkandroot\DoctrineBundle\Repository;

use Doctrine\DBAL\LockMode;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ObjectRepository;

/**
 * @template T of object
 * @extends ObjectRepository<T>
 */
interface CrudRepositoryInterface extends ObjectRepository
{
    /**
     * @param null $lockMode
     * @param null $lockVersion
     * @return T
     */
    public function fetch(mixed $id, LockMode|int|null $lockMode = null, int|null $lockVersion = null): object;

    /**
     * @param array<string, mixed> $criteria
     * @param array<string, string>|null $orderBy
     *
     * @return T
     */
    public function fetchOneBy(array $criteria, ?array $orderBy = null): object;

    /**
     * @param T $entity
     */
    public function create(object $entity, bool $flush = true): void;

    /**
     * @param T $entity
     */
    public function delete(object $entity, bool $flush = true): void;

    /**
     * @param array<string, mixed> $criteria
     * @param array<string, string>|null $orderBy
     *
     * @return Paginator<T>
     */
    public function findPaginatedBy(
        int $page = 1,
        int $perPage = 10,
        array $criteria = [],
        ?array $orderBy = null
    ): Paginator;

    public function flush(): void;
}
