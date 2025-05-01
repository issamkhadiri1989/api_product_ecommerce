<?php

namespace App\Controller\Api;

use App\Cart\Processor\CartProcessorInterface;
use App\DTO\Cart as CartDTO;
use App\Entity\Cart;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\Attribute\Groups;

final class CartController extends AbstractController
{
    #[Route('/checkout', name: 'app.cart_checkout', methods: ['POST'])]
    #[IsGranted('IS_AUTHENTICATED')]
    #[Groups(['api:read'])]
    public function checkout(
        #[MapRequestPayload] CartDTO $cart,
        CartProcessorInterface $processor,
    ): Cart {
        return $processor->process($cart);
    }
}
