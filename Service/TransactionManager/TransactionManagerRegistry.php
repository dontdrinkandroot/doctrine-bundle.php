<?php

namespace Dontdrinkandroot\DoctrineBundle\Service\TransactionManager;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Dontdrinkandroot\DoctrineBundle\Service\TransactionManager\TransactionManager;
use RuntimeException;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
class TransactionManagerRegistry
{
    private ManagerRegistry $registry;

    private $transactionManagersByName = [];

    public function __construct(ManagerRegistry $registry)
    {
        $this->registry = $registry;
    }

    public function getDefault(): TransactionManager
    {
        $defaultManager = $this->registry->getDefaultManagerName();

        return $this->getByName($defaultManager);
    }

    public function getByName(string $name): TransactionManager
    {
        $objectManager = $this->registry->getManager($name);
        if (array_key_exists($name, $this->transactionManagersByName)) {
            return $this->transactionManagersByName[$name];
        }

        assert($objectManager instanceof EntityManagerInterface);
        $transactionManager = new TransactionManager($objectManager);
        $this->transactionManagersByName[$name] = $transactionManager;

        return $transactionManager;
    }

    public function getByEntityManager(EntityManagerInterface $entityManager): TransactionManager
    {
        $registeredManagers = $this->registry->getManagers();

        foreach ($registeredManagers as $name => $registeredManager) {
            if ($registeredManager === $entityManager) {
                if (array_key_exists($name, $this->transactionManagersByName)) {
                    return $this->transactionManagersByName[$name];
                }

                $transactionManager = new TransactionManager($registeredManager);
                $this->transactionManagersByName[$name] = $transactionManager;

                return $transactionManager;
            }
        }

        throw new RuntimeException('EntityManager not found');
    }
}
