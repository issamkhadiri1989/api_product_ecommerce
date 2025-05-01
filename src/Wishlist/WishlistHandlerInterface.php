<?php

declare(strict_types=1);

namespace App\Wishlist;

use App\Entity\Account;
use App\Entity\Product;

interface WishlistHandlerInterface
{
    public function addToWishList(Account $user, Product $product): void;
}
