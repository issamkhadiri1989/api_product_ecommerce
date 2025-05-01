<?php

declare(strict_types=1);

namespace App\Account\Registration;

use App\Account\AccountInterface;
use App\Entity\Account;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AccountRegistrationManager implements AccountInterface
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly UserPasswordHasherInterface $hasher,
    ) {
    }

    public function register(Account $account): void
    {
        $this->doHashPassword($account);

        $this->entityManager->persist($account);
        $this->entityManager->flush();
    }

    private function doHashPassword(Account $account): void
    {
        $hashedPassword = $this->hasher->hashPassword($account, $account->getPassword());
        $account->setPassword($hashedPassword);
    }
}
