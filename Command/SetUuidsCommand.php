<?php

namespace Dontdrinkandroot\DoctrineBundle\Command;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Dontdrinkandroot\Entity\UuidEntityInterface;
use Dontdrinkandroot\Event\Listener\UuidEntityListener;
use Dontdrinkandroot\Repository\OrmEntityRepository;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SetUuidsCommand extends Command
{
    /**
     * @var \Dontdrinkandroot\Event\Listener\UuidEntityListener
     */
    private $uuidEntityListener;

    /**
     * @var Registry
     */
    private $registry;

    public function __construct(Registry $registry)
    {
        parent::__construct();
        $this->registry = $registry;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->addArgument('entity', InputArgument::REQUIRED);
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $entityName = $input->getArgument('entity');

        $repository = $this->registry->getRepository($entityName);
        $entityManager  = $this->registry->getManagerForClass($entityName);

        $entities = $repository->findAll();
        foreach ($entities as $entity) {
            if (is_a($entity, UuidEntityInterface::class)) {
                /** @var UuidEntityInterface $uuidEntity */
                $uuidEntity = $entity;
                if (null === $uuidEntity->getUuid()) {
                    $uuidEntity->setUuid(Uuid::uuid4()->toString());
                }
            }
        }
        $entityManager->flush();
    }
}
