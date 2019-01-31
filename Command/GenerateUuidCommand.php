<?php

namespace Dontdrinkandroot\DoctrineBundle\Command;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Dontdrinkandroot\DoctrineBundle\Event\Listener\UuidEntityListener;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateUuidCommand extends Command
{
    /**
     * @var UuidEntityListener
     */
    private $uuidEntityListener;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(UuidEntityListener $uuidEntityListener, EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->uuidEntityListener = $uuidEntityListener;
        $this->entityManager = $entityManager;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->addOption('strategy', null, InputOption::VALUE_REQUIRED);
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $strategy = $this->uuidEntityListener->getStrategy();
        if ($input->getOption('strategy')) {
            $strategy = $input->getOption('strategy');
        }

        $output->writeln($this->uuidEntityListener->generateUuid($this->entityManager, $strategy));
    }
}
