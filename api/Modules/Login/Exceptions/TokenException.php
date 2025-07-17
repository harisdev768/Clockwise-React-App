<?php
namespace App\Modules\Login\Exceptions;

use Exception;

class TokenException extends Exception
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

    public static function missingToken(): self
    {
        return new self("No Token Provided", 422);
    }
    public static function invalidToken(): self
    {
        return new self("Invalid Token", 422);
    }

    public static function tokenExpired(): self
    {
        return new self("Token has expired", 401);
    }

}
