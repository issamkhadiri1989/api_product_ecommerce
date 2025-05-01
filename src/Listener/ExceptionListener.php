<?php

declare(strict_types=1);

namespace App\Listener;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

#[AsEventListener(event: 'kernel.exception', method: 'onExceptionOccurred', priority: 50)]
class ExceptionListener
{
    public function onExceptionOccurred(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        $jsonResponse = new JsonResponse([
            'message' => $exception->getMessage(),
        ]);

        $event->setResponse($jsonResponse);
    }
}
