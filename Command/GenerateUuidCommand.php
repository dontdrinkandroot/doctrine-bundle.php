<?php

namespace Dontdrinkandroot\DoctrineBundle\Command;

use Doctrine\ORM\EntityManager;
use Dontdrinkandroot\DoctrineBundle\Event\Listener\UuidEntityListener;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateUuidCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('ddr:doctrine:generate-uuid')
            ->addOption('strategy', null, InputOption::VALUE_REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var UuidEntityListener $uuidListener */
        $uuidListener = $this->getContainer()->get('ddr.doctrine.event.listener.uuid');
        $strategy = $uuidListener->getStrategy();
        if ($input->getOption('strategy')) {
            $strategy = $input->getOption('strategy');
        }

        /** @var EntityManager $entityManager */
        $entityManager = $this->getContainer()->get('doctrine.orm.entity_manager');

        $output->writeln($uuidListener->generateUuid($entityManager, $strategy));
    }
}
