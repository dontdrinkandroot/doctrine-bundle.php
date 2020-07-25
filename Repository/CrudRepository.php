<?php

namespace Dontdrinkandroot\DoctrineBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Dontdrinkandroot\DoctrineBundle\Repository\CrudRepositoryInterface;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
class CrudRepository extends ServiceEntityRepository implements CrudRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function create($entity, bool $flush = true)
    {
        $this->getEntityManager()->persist($entity);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function delete($entity, bool $flush = true)
    {
        $this->getEntityManager()->remove($entity);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * {@inheritdoc}
     */
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

        return new Paginator($queryBuilder);
    }
}
