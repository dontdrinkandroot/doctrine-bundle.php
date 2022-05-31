<?php

namespace Dontdrinkandroot\DoctrineBundle\Service\TransactionManager;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Dontdrinkandroot\Common\Asserted;
use RuntimeException;

class TransactionManagerRegistry
{
    /** @var array<string,TransactionManager> */
    private array $transactionManagersByName = [];

    public function __construct(private ManagerRegistry $registry)
    {
    }

    public function getDefault(): TransactionManager
    {
        $defaultManager = $this->registry->getDefaultManagerName();

        return $this->getByName($defaultManager);
    }

    public function getByName(string $name): TransactionManager
    {
        if (array_key_exists($name, $this->transactionManagersByName)) {
            return $this->transactionManagersByName[$name];
        }

        $entityManager = Asserted::instanceOf($this->registry->getManager($name), EntityManagerInterface::class);
        $transactionManager = new TransactionManager($entityManager);
        $this->transactionManagersByName[$name] = $transactionManager;

        return $transactionManager;
    }

    public function getByEntityManager(EntityManagerInterface $entityManager): TransactionManager
    {
        /** @var array<string,ObjectManager> $registeredManagers */
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
