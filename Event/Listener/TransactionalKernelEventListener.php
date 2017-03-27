<?php

namespace Dontdrinkandroot\DoctrineBundle\Event\Listener;

use Dontdrinkandroot\Repository\TransactionManager;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\FinishRequestEvent;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Event\PostResponseEvent;

class TransactionalKernelEventListener
{
    /**
     * @var LoggerInterface;
     */
    private $logger;

    /**
     * @var TransactionManager
     */
    private $transactionManager;

    private $rollbackCodes = [];

    public function __construct(TransactionManager $transactionManager)
    {
        $this->transactionManager = $transactionManager;
        $this->logger = new NullLogger();
        $this->rollbackCodes[] = Response::HTTP_BAD_REQUEST;
        $this->rollbackCodes[] = Response::HTTP_INTERNAL_SERVER_ERROR;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        $this->getLogger()->info('Kernel Transaction: Begin');
        $this->transactionManager->beginTransaction();
    }

    public function onKernelFinishRequest(FinishRequestEvent $event)
    {
        $this->getLogger()->info('Kernel Transaction: Commit');
        $this->transactionManager->commitTransaction();
    }

    public function onKernelTerminate(PostResponseEvent $event)
    {
        $response = $event->getResponse();
        $statusCode = (int)$response->getStatusCode();
        if (in_array($statusCode, $this->rollbackCodes)) {
            $this->getLogger()->info('Kernel Transaction: Rollback by statusCode', ['statusCode' => $statusCode]);
            $this->transactionManager->rollbackTransaction();
        }
    }

    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $this->getLogger()->info('Kernel Transaction: Rollback by exception', [$event->getException()->getMessage()]);
        $this->transactionManager->rollbackTransaction();
    }

    /**
     * @return LoggerInterface
     */
    public function getLogger()
    {
        return $this->logger;
    }

    /**
     * @param LoggerInterface $logger
     */
    public function setLogger($logger)
    {
        $this->logger = $logger;
    }
}
