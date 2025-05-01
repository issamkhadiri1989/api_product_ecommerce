<?php

declare(strict_types=1);

namespace App\Account;

use App\Entity\Account;

interface AccountInterface
{
    public function register(Account $account): void;
}
