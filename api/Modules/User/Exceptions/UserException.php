<?php
namespace App\Modules\User\Exceptions;

use Exception;

class UserException extends Exception
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

    public static function missingCredentials(): self
    {
        return new self("Missing Credentials", 422);
    }

    public static function unauthorized(): self
    {
        return new self("Invalid Credentials", 401);
    }


}
