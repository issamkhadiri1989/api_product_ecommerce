<?php

namespace App\Controller\Api;

use App\Entity\Account;
use App\Entity\Product;
use App\Wishlist\WishlistHandlerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\Attribute\Groups;

#[Route(path: '/wishlist', name: 'app.wishlist.', methods: ['DELETE'])]
final class WishlistController extends AbstractController
{
    #[Route('/{id}', name: 'add', methods: ['POST'])]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    #[Groups(['app:profile:public'])]
    public function index(
        #[MapEntity] Product $product,
        WishlistHandlerInterface $wishlistHandler,
    ): Account {
        /** @var Account $user */
        $user = $this->getUser();

        $wishlistHandler->addToWishList($user, $product);

        return $user;
    }
}
