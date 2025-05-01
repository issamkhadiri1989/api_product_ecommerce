<?php

namespace App\Controller;

use App\Account\Registration\AccountRegistrationManager;
use App\Entity\Account;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

final class AccountController extends AbstractController
{
    public function __construct(
        private readonly AccountRegistrationManager $accountRegistrationManager,
    ) {
    }

    #[Route('/account', name: 'app_account', methods: ['POST'])]
    public function __invoke(
        #[MapRequestPayload] Account $account,
    ): JsonResponse {
        $this->accountRegistrationManager->register($account);

        return $this->json([
            'message' => 'Your account has been created.',
        ], status: Response::HTTP_CREATED);
    }
}
