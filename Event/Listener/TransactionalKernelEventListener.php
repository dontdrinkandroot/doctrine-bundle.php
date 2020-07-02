<?php

namespace Dontdrinkandroot\DoctrineBundle\Event\Listener;

use Dontdrinkandroot\Repository\TransactionManager;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Event\FinishRequestEvent;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Event\PostResponseEvent;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\TerminateEvent;

/**
 * Implements the Session in View Pattern.
 *
 * @author Philip Washington Sorst <philip@sorst.net>
 */
class TransactionalKernelEventListener
{
    private LoggerInterface $logger;

    private TransactionManager $transactionManager;

    private $rollbackCodes = [
        Response::HTTP_BAD_REQUEST,
        Response::HTTP_INTERNAL_SERVER_ERROR
    ];

    public function __construct(TransactionManager $transactionManager)
    {
        $this->logger = new NullLogger();
        $this->transactionManager = $transactionManager;
    }

    public function onKernelRequest(RequestEvent $event)
    {
        if ($event->isMasterRequest()) {
            $this->getLogger()->info('Kernel Transaction: Begin');
            $this->transactionManager->beginTransaction();
        }
    }

    public function onKernelFinishRequest(FinishRequestEvent $event)
    {
        if ($event->isMasterRequest()) {
            $this->getLogger()->info('Kernel Transaction: Commit');
            $this->transactionManager->commitTransaction();
        }
    }

    public function onKernelTerminate(TerminateEvent $event)
    {
        if ($event->isMasterRequest()) {
            $response = $event->getResponse();
            $statusCode = $response->getStatusCode();
            if (in_array($statusCode, $this->rollbackCodes)) {
                $this->getLogger()->info('Kernel Transaction: Rollback by statusCode', ['statusCode' => $statusCode]);
                $this->transactionManager->rollbackTransaction();
            }
        }
    }

    public function onKernelException(ExceptionEvent $event)
    {
        if ($event->isMasterRequest()) {
            $this->getLogger()->info(
                'Kernel Transaction: Rollback by exception',
                [$event->getThrowable()->getMessage()]
            );
            $this->transactionManager->rollbackTransaction();
        }
    }

    public function getLogger(): LoggerInterface
    {
        return $this->logger;
    }

    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }
}
