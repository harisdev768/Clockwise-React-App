<?php
namespace App\Modules\ForgotPassword\Exceptions;

use Exception;

class ResetPasswordException extends Exception
{
    protected int $statusCode;

    public function __construct(string $message = "Bad Request", int $statusCode = 400)
    {
        parent::__construct($message);
        $this->statusCode = $statusCode;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function toArray(): array
    {
        return [
            'success' => false,
            'message' => $this->getMessage(),
            'code' => $this->getCode(),
        ];
    }

    // Methods for specific login errors

    public static function missingCredentials(): self
    {
        return new self("Token and new password are required", 422);
    }
    public static function tokenExpired(): self{
        return new self("Token is invalid or expired. Please try again.", 400);
    }
}
