<?php

declare(strict_types=1);

namespace App\Security\Voter;

use App\Entity\Account;
use App\Entity\Product;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class AdminVoter extends Voter
{
    public function __construct(
        #[Autowire(env: 'APP_ADMIN_EMAIL')]
        private readonly string $adminEmailAddress,
    ) {
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        return true === \in_array($attribute, ['CAN_EDIT', 'CAN_ADD', 'CAN_DELETE'])
            && $subject instanceof Product;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        /** @var Account $user */
        $user = $token->getUser();

        if (null === $user) {
            return false;
        }

        return $user->getEmail() === $this->adminEmailAddress;
    }
}
