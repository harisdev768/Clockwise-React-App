<?php
namespace App\Modules\Login\Helpers;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JWTHandler {
    private string $secret = 'your_super_secret_key';
    private string $issuer = 'clockwise-api';
    private string $audience = 'clockwise-users';
    private int $expiry = 3600; // 1 hour

    public function generateToken(array $payload): string {
        $issuedAt = time();
        $tokenPayload = array_merge($payload, [
            'iss' => $this->issuer,
            'aud' => $this->audience,
            'iat' => $issuedAt,
            'exp' => $issuedAt + $this->expiry
        ]);

        return JWT::encode($tokenPayload, $this->secret, 'HS256');
    }

    public function verifyToken(string $jwt): array|false {
        try {
            $decoded = JWT::decode($jwt, new Key($this->secret, 'HS256'));
            return (array) $decoded;
        } catch (\Exception $e) {
            return false;
        }
    }
}
