<?php

namespace Dontdrinkandroot\DoctrineBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Override;

/**
 * @template T of object
 * @implements CrudRepositoryInterface<T>
 * @extends ServiceEntityRepository<T>
 */
class CrudRepository extends ServiceEntityRepository implements CrudRepositoryInterface
{
    #[Override]
    public function fetch($id, $lockMode = null, $lockVersion = null): object
    {
        $result = parent::find($id, $lockMode, $lockVersion);
        $this->assertResultFound($result);
        return $result;
    }

    #[Override]
    public function fetchOneBy(array $criteria, array $orderBy = null): object
    {
        $result = parent::findOneBy($criteria, $orderBy);
        $this->assertResultFound($result);
        return $result;
    }

    #[Override]
    public function create($entity, bool $flush = true): void
    {
        $this->getEntityManager()->persist($entity);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    #[Override]
    public function delete($entity, bool $flush = true): void
    {
        $this->getEntityManager()->remove($entity);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    #[Override]
    public function findPaginatedBy(
        int $page = 1,
        int $perPage = 10,
        array $criteria = [],
        array $orderBy = null
    ): Paginator {
        $queryBuilder = $this->createQueryBuilder('entity');

        foreach ($criteria as $field => $value) {
            $queryBuilder->andWhere('entity.' . $field . ' = :' . $field);
            $queryBuilder->setParameter($field, $value);
        }

        if (null !== $orderBy) {
            foreach ($orderBy as $field => $order) {
                $queryBuilder->addOrderBy('entity.' . $field, $order);
            }
        }

        $queryBuilder->setFirstResult(($page - 1) * $perPage);
        $queryBuilder->setMaxResults($perPage);

        /** @var Paginator<T> */
        return new Paginator($queryBuilder);
    }

    #[Override]
    public function flush(): void
    {
        $this->getEntityManager()->flush();
    }

    #[Override]
    public function clear(): void
    {
        $this->getEntityManager()->clear();
    }

    /**
     * @param T|null $result
     *
     * @throws NoResultException
     */
    protected function assertResultFound(?object $result): void
    {
        if (null === $result) {
            throw new NoResultException();
        }
    }
}
