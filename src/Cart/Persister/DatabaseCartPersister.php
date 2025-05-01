<?php

declare(strict_types=1);

namespace App\Cart\Persister;

use App\Entity\Cart;
use App\Entity\CartItem;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;

class DatabaseCartPersister implements CartPersisterInterface
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    public function persist(Cart $cart): void
    {
        $identifiers = [];
        /** @var CartItem $cartItem */
        foreach ($cart->getItems() as $cartItem) {
            $cartItem->setCart($cart);
            $this->entityManager->persist($cartItem);

            $originalProductIdentifier = $cartItem->getProduct()->getIdentifier();
            $identifiers[$originalProductIdentifier] = $cartItem->getQuantity();
        }

        $this->entityManager->persist($cart);

        // deduct quantities from the original products.
        $this->deductQuantityFromStock($identifiers);

        $this->entityManager->flush();
    }

    private function deductQuantityFromStock(array $identifiers): void
    {
        /** @var Product[] $loadProducts */
        $loadedProducts = $this->entityManager
            ->getRepository(Product::class)
            ->loadProducts(\array_keys($identifiers));

        /** @var Product $product */
        foreach ($loadedProducts as $product) {
            $newQuantity = $product->getQuantity() - $identifiers[$product->getId()];
            $product->setQuantity($newQuantity > 0 ? $newQuantity : 0);
        }
    }
}
