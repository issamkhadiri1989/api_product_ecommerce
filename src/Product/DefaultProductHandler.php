<?php

declare(strict_types=1);

namespace App\Product;

use App\Entity\Product;
use App\Exception\UnprocessableEntityException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class DefaultProductHandler implements ProductHandlerInterface
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly ValidatorInterface $validator,
        private readonly RequestStack $requestStack,
        private readonly SerializerInterface $serializer,
    ) {
    }

    public function remove(Product $product): void
    {
        $this->entityManager->remove($product);
        $this->entityManager->flush();
    }

    public function add(Product $product): void
    {
        $violations = $this->validator->validate($product);

        if ($violations->count() > 0) {
            throw new UnprocessableEntityException($violations);
        }

        $this->entityManager->persist($product);
        $this->entityManager->flush();
    }

    public function edit(Product $product): void
    {
        $product = $this->hydrateProductFromRequest($product);

        $violations = $this->validator->validate($product);

        if ($violations->count() > 0) {
            throw new UnprocessableEntityException($violations);
        }

        $this->entityManager->flush();
    }

    private function hydrateProductFromRequest(Product $product): Product
    {
        $request = $this->requestStack->getCurrentRequest();
        $content = $request->getContent();

        $this->serializer->deserialize(data: $content, type: Product::class, format: 'json', context: [
            'object_to_populate' => $product,
        ]);

        return $product;
    }
}
