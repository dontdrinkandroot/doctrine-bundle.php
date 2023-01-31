<?php

namespace Dontdrinkandroot\DoctrineBundle\Event\Listener;

use Dontdrinkandroot\DoctrineBundle\Service\TransactionManager\TransactionManagerRegistry;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Event\FinishRequestEvent;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\TerminateEvent;

/**
 * Implements the Session in View Pattern.
 */
class TransactionalKernelEventListener
{
    private LoggerInterface $logger;

    /**
     * @param list<string>               $enabledManagerNames
     * @param list<int>                  $rollbackCodes
     */
    public function __construct(
        private readonly TransactionManagerRegistry $transactionManagerRegistry,
        private readonly array $enabledManagerNames = [],
        private readonly array $rollbackCodes = []
    ) {
        $this->logger = new NullLogger();
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        if ($event->isMainRequest()) {
            foreach ($this->enabledManagerNames as $enabledManagerName) {
                $transactionManager = $this->transactionManagerRegistry->getByName($enabledManagerName);
                $this->getLogger()->info('Kernel Transaction: Begin', ['entitymanager' => $enabledManagerName]);
                $transactionManager->beginTransaction();
            }
        }
    }

    public function onKernelFinishRequest(FinishRequestEvent $event): void
    {
        if ($event->isMainRequest()) {
            foreach ($this->enabledManagerNames as $enabledManagerName) {
                $transactionManager = $this->transactionManagerRegistry->getByName($enabledManagerName);
                $this->getLogger()->info('Kernel Transaction: Commit', ['entitymanager' => $enabledManagerName]);
                $transactionManager->commitTransaction();
            }
        }
    }

    public function onKernelTerminate(TerminateEvent $event): void
    {
        if ($event->isMainRequest()) {
            $response = $event->getResponse();
            $statusCode = $response->getStatusCode();
            if (in_array($statusCode, $this->rollbackCodes, true)) {
                foreach ($this->enabledManagerNames as $enabledManagerName) {
                    $transactionManager = $this->transactionManagerRegistry->getByName($enabledManagerName);
                    $this->getLogger()->info(
                        'Kernel Transaction: Rollback by statusCode',
                        ['entitymanager' => $enabledManagerName, 'statusCode' => $statusCode]
                    );
                    $transactionManager->rollbackTransaction();
                }
            }
        }
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        if ($event->isMainRequest()) {
            foreach ($this->enabledManagerNames as $enabledManagerName) {
                $transactionManager = $this->transactionManagerRegistry->getByName($enabledManagerName);
                $this->getLogger()->info(
                    'Kernel Transaction: Rollback by exception',
                    ['entitymanager' => $enabledManagerName, $event->getThrowable()->getMessage()]
                );
                $transactionManager->rollbackTransaction();
            }
        }
    }

    public function getLogger(): LoggerInterface
    {
        return $this->logger;
    }

    public function setLogger(LoggerInterface $logger): void
    {
        $this->logger = $logger;
    }
}
