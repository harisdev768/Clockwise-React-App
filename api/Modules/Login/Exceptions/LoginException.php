<?php
namespace App\Modules\Login\Exceptions;

use Exception;

class LoginException extends Exception
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
        return new self("Email and password are required", 422);
    }

    public static function unauthorized(): self
    {
        return new self("Invalid email or password", 401);
    }
    public static function tokenExpired(): self
    {
        return new self("Token has expired", 401);
    }
}
