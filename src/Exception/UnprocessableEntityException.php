<?php

declare(strict_types=1);

namespace App\Exception;

use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Validator\ConstraintViolationList;

class UnprocessableEntityException extends UnprocessableEntityHttpException
{
    private ConstraintViolationList $violations;

    public function __construct(ConstraintViolationList $violations, string $message = '', ?\Throwable $previous = null, int $code = 0, array $headers = [])
    {
        parent::__construct($message, $previous, $code, $headers);

        $this->violations = $violations;
    }

    public function getViolations(): ConstraintViolationList
    {
        return $this->violations;
    }
}
