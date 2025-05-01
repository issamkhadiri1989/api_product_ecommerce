<?php

declare(strict_types=1);

namespace App\Cart\Persister;

use App\Entity\Cart;

interface CartPersisterInterface
{
    public function persist(Cart $cart): void;
}
