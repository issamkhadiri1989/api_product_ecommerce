<?php

declare(strict_types=1);

namespace App\Cart\Processor;

use App\DTO\Cart;
use App\Entity\Cart as CartEntity;

interface CartProcessorInterface
{
    public function process(Cart $cart): CartEntity;
}
