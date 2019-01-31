<?php

namespace Dontdrinkandroot\DoctrineBundle\Command;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Dontdrinkandroot\DoctrineBundle\Event\Listener\UuidEntityListener;
use Dontdrinkandroot\Entity\UuidEntityInterface;
use Dontdrinkandroot\Repository\OrmEntityRepository;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class SetUuidsCommand extends Command
{
    /**
     * @var UuidEntityListener
     */
    private $uuidEntityListener;

    /**
     * @var Registry
     */
    private $registry;

    public function __construct(Registry $registry, UuidEntityListener $uuidEntityListener)
    {
        parent::__construct();
        $this->uuidEntityListener = $uuidEntityListener;
        $this->registry = $registry;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->addArgument('entity', InputArgument::REQUIRED)
            ->addOption('strategy', null, InputOption::VALUE_REQUIRED);
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var UuidEntityListener $uuidListener */
        $strategy = $this->uuidEntityListener->getStrategy();
        if ($input->getOption('strategy')) {
            $strategy = $input->getOption('strategy');
        }

        $entityName = $input->getArgument('entity');

        $repository = $this->registry->getRepository($entityName);
        $entityManager  = $this->registry->getManagerForClass($entityName);


        $entities = $repository->findAll();
        foreach ($entities as $entity) {
            if (is_a($entity, UuidEntityInterface::class)) {
                /** @var UuidEntityInterface $uuidEntity */
                $uuidEntity = $entity;
                if (null === $uuidEntity->getUuid()) {
                    $uuidEntity->setUuid($uuidListener->generateUuid($entityManager, $strategy));
                }
                $repository->flush($entity);
            }
        }
    }
}
