<?php

declare(strict_types=1);

namespace App\Cart\Factory;

use App\Entity\CartItem;
use App\Entity\Product;
use App\Entity\ProductItem;

class CartItemFactory extends AbstractCartItemFactory
{
    protected function createCartItem(Product $product, int $quantity): CartItem
    {
        $cartItem = new CartItem();

        $productItem = new ProductItem();
        $productItem->setCode($product->getCode())
            ->setIdentifier($product->getId())
            ->setName($product->getName())
            ->setCategory($product->getCategory())
            ->setQuantityInStock($product->getQuantity())
            ->setInternationalReference($product->getInternationalReference());

        $cartItem
            ->setProduct($productItem)
            ->setQuantity($quantity);

        return $cartItem;
    }
}
