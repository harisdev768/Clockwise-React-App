<?php

namespace App\Modules\Login\Services;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JWTService
{
    private string $secretKey;
    private string $issuer;
    private string $audience;
    private int $expiration;

    public function __construct()
    {
        $this->secretKey = 'loc@calhost'; // store this securely!
        $this->issuer = 'clockwise.local';
        $this->audience = 'clockwise-users';
        $this->expiration = 10; // 1 hour - in secs
    }

    public function generateToken(array $payload): string
    {
        $issuedAt = time();

        $token = [
            'iss' => $this->issuer,
            'aud' => $this->audience,
            'iat' => $issuedAt,
            'exp' => $issuedAt + $this->expiration,
            'data' => $payload
        ];

        return JWT::encode($token, $this->secretKey, 'HS256');
    }

    public function verifyToken(string $token): ?array
    {
        try {
            $decoded = JWT::decode($token, new Key($this->secretKey, 'HS256'));
            return (array)$decoded->data;
        } catch (\Exception $e) {
            return null;
        }
    }
}
