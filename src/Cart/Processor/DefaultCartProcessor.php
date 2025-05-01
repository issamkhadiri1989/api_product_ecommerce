<?php

declare(strict_types=1);

namespace App\Cart\Processor;

use App\Cart\Factory\CartFactory;
use App\Cart\Persister\CartPersisterInterface;
use App\DTO\Cart;
use App\Entity\Cart as CartEntity;
use App\Exception\UnprocessableEntityException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class DefaultCartProcessor implements CartProcessorInterface
{
    public function __construct(
        private readonly CartFactory $cartFactory,
        private readonly ValidatorInterface $validator,
        private readonly CartPersisterInterface $cartPersister,
    ) {
    }

    public function process(Cart $cart): CartEntity
    {
        $cartEntity = $this->cartFactory->getCartInstanceFrom($cart);

        // validate the cart
        $violations = $this->validator->validate($cartEntity);

        if ($violations->count() > 0) {
            throw new UnprocessableEntityException($violations);
        }

        $this->cartPersister->persist($cartEntity);

        return $cartEntity;
    }
}
