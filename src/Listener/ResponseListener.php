<?php

declare(strict_types=1);

namespace App\Listener;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

#[AsEventListener(event: KernelEvents::VIEW, priority: 1000, method: 'onResponseEvent')]
#[AsEventListener(event: KernelEvents::CONTROLLER, priority: 1000, method: 'onControllerEvent')]
class ResponseListener
{
    public function __construct(private readonly SerializerInterface $serializer)
    {
    }

    /**
     * @throws \ReflectionException
     */
    public function onControllerEvent(ControllerEvent $event): void
    {
        $controller = $event->getController();

        if (!\is_array($controller)) {
            return;
        }

        $callable = $controller[1] ? \get_class($controller[0]).'::'.$controller[1] : $controller[0];

        $class = new \ReflectionMethod($callable);
        $attributes = $class->getAttributes(Groups::class);

        $groupsContext = ['groups' => []];

        foreach ($attributes as $attribute) {
            $arguments = $attribute->getArguments();
            $groupsContext['groups'] = array_merge($groupsContext['groups'], ...$arguments);
        }

        $event->getRequest()->attributes->set('_serialization_context', $groupsContext);
    }

    public function onResponseEvent(ViewEvent $event): void
    {
        $value = $event->getControllerResult();

        $request = $event->getRequest();

        $statusCode = match ($request->getMethod()) {
            Request::METHOD_POST => 201,
            Request::METHOD_DELETE => 204,
        };

        $groups = $request->attributes->get('_serialization_context');

        $response = new JsonResponse($this->serializer->serialize($value, 'json', context: [
            AbstractNormalizer::GROUPS => $groups['groups'],
            AbstractObjectNormalizer::ENABLE_MAX_DEPTH => true,
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function (object $object, ?string $format, array $context): int {
                return $object->getId();
            },
        ]), json: true, status: $statusCode);

        $event->setResponse($response);
    }
}
