<?php

declare(strict_types=1);

namespace App\Listener;

use App\Exception\UnprocessableEntityException;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\Serializer\SerializerInterface;

#[AsEventListener(event: 'kernel.exception', method: 'onKernelException', priority: 100)]
class ValidationListener
{
    public function __construct(private readonly SerializerInterface $serializer)
    {
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        // only handles the custom UnprocessableEntityException
        if ($exception instanceof UnprocessableEntityException) {
            $response = new JsonResponse();
            $response->setStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY);

            $json = $this->serializer->serialize($exception->getViolations(), 'json');
            $response->setJson($json);

            $event->setResponse($response);
        }
    }
}
