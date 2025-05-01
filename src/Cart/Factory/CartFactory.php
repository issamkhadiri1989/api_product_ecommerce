<?php

declare(strict_types=1);

namespace App\Cart\Factory;

use App\DTO\Cart as CartDTO;
use App\Entity\Cart;
use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

class CartFactory extends AbstractCartFactory
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly CartItemFactory $cartItemFactory,
        Security $security,
    ) {
        parent::__construct($security);
    }

    protected function buildCart(CartDTO $cartDTO): Cart
    {
        $cart = new Cart();

        $normalizedCartItems = $this->doRunInternalProcess($cartDTO);

        // load products
        /** @var ProductRepository $repository */
        $repository = $this->entityManager->getRepository(Product::class);
        $products = $repository->loadProducts(\array_keys($normalizedCartItems));

        return $this->doAddItemsToCart($products, $cart, $normalizedCartItems);
    }

    private function doAddItemsToCart(array $products, Cart $cart, array $normalizedCartItems): Cart
    {
        \array_walk($products, function (Product &$product) use ($cart, $normalizedCartItems) {
            $orderedQuantity = $normalizedCartItems[$product->getId()];
            $this->cartItemFactory->addProductToCart(
                $cart,
                $product,
                $orderedQuantity,
            );
        });

        return $cart;
    }

    private function doRunInternalProcess(CartDTO $cart): array
    {
        $items = $cart->items;

        $products = [];

        foreach ($items as $item) {
            $products[$item->product] ??= 0;
            $products[$item->product] += $item->quantity;
        }

        return $products;
    }
}
