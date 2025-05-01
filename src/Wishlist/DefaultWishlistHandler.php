<?php

declare(strict_types=1);

namespace App\Wishlist;

use App\Entity\Account;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;

class DefaultWishlistHandler implements WishlistHandlerInterface
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    public function addToWishList(Account $user, Product $product): void
    {
        if ($user->getFavoriteItems()->contains($product)) {
            return;
        }

        $user->addFavoriteItem($product);
        $this->entityManager->flush();
    }
}
