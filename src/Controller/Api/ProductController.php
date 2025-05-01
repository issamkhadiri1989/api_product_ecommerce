<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Entity\Product;
use App\Product\ProductHandlerInterface;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route(name: 'app.products.', path: '/products')]
#[AsController]
#[IsGranted('IS_AUTHENTICATED_REMEMBERED')]
class ProductController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly ProductHandlerInterface $handler,
    ) {
    }

    /**
     * Retrieve all products.
     */
    #[Route('/', name: 'get_all', methods: ['GET'])]
    public function getAllRecords(): array
    {
        /** @var ProductRepository $repository */
        $repository = $this->entityManager->getRepository(Product::class);

        return $repository->retrieveProducts();
    }

    /**
     * Retrieve an element.
     */
    #[Route(path: '/{id}', name: 'get_by_id', methods: ['GET'])]
    public function getProductById(#[MapEntity] Product $product): Product
    {
        return $product;
    }

    /**
     * Apply a partial modification to an element.
     */
    #[Route(path: '/{id}', name: 'patch_product', methods: ['PATCH'])]
    /* #[IsGranted(new Expression('user.getEmail() == "admin@admin.com"'))] <-- one solution  is to use expression but this does not respect SOLID */
    #[IsGranted('CAN_EDIT', 'product')]
    public function updateProduct(#[MapEntity] Product $product): Product
    {
        $this->handler->edit($product);

        return $product;
    }

    /**
     * Delete a product.
     */
    #[Route(path: '/{id}', name: 'delete_product', methods: ['DELETE'])]
    #[IsGranted('CAN_DELETE', 'product')]
    public function deleteProduct(#[MapEntity(message: 'Not found')] Product $product): Product
    {
        $this->handler->remove($product);

        return $product;
    }

    /**
     * Create new product.
     */
    #[Route(path: '/', name: 'create_new', methods: ['POST'])]
    public function createNewProduct(
        #[MapRequestPayload] Product $product,
    ): Product {
        $this->handler->add($product);

        return $product;
    }
}
