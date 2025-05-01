<?php

declare(strict_types=1);

namespace App\Cart\Factory;

use App\Entity\Cart;
use App\Entity\CartItem;
use App\Entity\Product;

abstract class AbstractCartItemFactory
{
    abstract protected function createCartItem(Product $product, int $quantity): CartItem;

    public function addProductToCart(Cart $cart, Product $product, int $quantity): Cart
    {
        $item = $this->createCartItem($product, $quantity);

        $cart->addItem($item);

        return $cart;
    }
}
