<?php

namespace App\Modules\Login\Services;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JWTService {
    private string $secret;
    private string $algo;
    private int $expiry;

    public function __construct() {
        $this->secret = 'loc@lhost';
        $this->algo = 'HS256';
        $this->expiry = 3600; // 1 hour
    }

    public function generateToken(array $payload): string {
        $payload['iat'] = time();   
        $payload['exp'] = time() + $this->expiry;

        return JWT::encode($payload, $this->secret, $this->algo);
    }

    public function validateToken(string $token): array {
        return (array) JWT::decode($token, new Key($this->secret, $this->algo));
    }
}
