<?php

declare(strict_types=1);

namespace App\Cart\Factory;

use App\DTO\Cart as CartDTO;
use App\Entity\Account;
use App\Entity\Cart;
use App\Enum\CartStatus;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

abstract class AbstractCartFactory
{
    public function __construct(protected readonly Security $security)
    {
    }

    abstract protected function buildCart(CartDTO $cartDTO): Cart;

    public function getCartInstanceFrom(CartDTO $cartDTO): Cart
    {
        /** @var Account $owner */
        $owner = $this->security->getUser();

        if (null === $owner) {
            throw new AccessDeniedHttpException();
        }

        $cart = $this->buildCart($cartDTO);

        $cart->setCreatedAt(new \DateTimeImmutable())
            ->setOwner($owner)
            ->setStatus(CartStatus::PLACED);

        return $cart;
    }
}
