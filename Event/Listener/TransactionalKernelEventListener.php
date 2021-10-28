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

    private TransactionManagerRegistry $transactionManagerRegistry;

    private array $enabledManagerNames;

    private array $rollbackCodes;

    public function __construct(
        TransactionManagerRegistry $transactionManagerRegistry,
        array $enabledManagerNames = [],
        array $rollbackCodes = []
    ) {
        $this->logger = new NullLogger();
        $this->transactionManagerRegistry = $transactionManagerRegistry;
        $this->enabledManagerNames = $enabledManagerNames;
        $this->rollbackCodes = $rollbackCodes;
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        if ($event->isMasterRequest()) {
            foreach ($this->enabledManagerNames as $enabledManagerName) {
                $transactionManager = $this->transactionManagerRegistry->getByName($enabledManagerName);
                $this->getLogger()->info('Kernel Transaction: Begin', ['entitymanager' => $enabledManagerName]);
                $transactionManager->beginTransaction();
            }
        }
    }

    public function onKernelFinishRequest(FinishRequestEvent $event): void
    {
        if ($event->isMasterRequest()) {
            foreach ($this->enabledManagerNames as $enabledManagerName) {
                $transactionManager = $this->transactionManagerRegistry->getByName($enabledManagerName);
                $this->getLogger()->info('Kernel Transaction: Commit', ['entitymanager' => $enabledManagerName]);
                $transactionManager->commitTransaction();
            }
        }
    }

    public function onKernelTerminate(TerminateEvent $event): void
    {
        if ($event->isMasterRequest()) {
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
        if ($event->isMasterRequest()) {
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
