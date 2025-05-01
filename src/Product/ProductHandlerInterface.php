<?php

declare(strict_types=1);

namespace App\Product;

use App\Entity\Product;

interface ProductHandlerInterface
{
    public function remove(Product $product): void;

    public function add(Product $product): void;

    public function edit(Product $product): void;
}
