<?php
namespace App\Modules\ForgotPassword\Exceptions;

use Exception;

class ForgotPasswordException extends Exception
{
    protected int $statusCode;

    public function __construct(string $message = "Bad Request", int $statusCode = 400)
    {
        parent::__construct($message);
        $this->statusCode = $statusCode;
        http_response_code($this->statusCode);
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

    public static function missingEmail(): self
    {
        return new self("Email and password are required", 422);
    }

    public static function invalidEmail(): self
    {
        return new self("If your email is registered, you will receive a reset link.", 401);
    }
}
