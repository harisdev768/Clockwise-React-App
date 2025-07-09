<?php
namespace App\Modules\Login\Controllers;

use App\Modules\Login\Services\JWTService;

class JWTController {
    private JWTService $jwtService;

    public function __construct(JWTService $jwtService) {
        $this->jwtService = $jwtService;
    }

    public function authenticate(): void {
        $token = $_COOKIE['jwt'] ?? null;

        if (!$token) {
            echo json_encode(['success' => false, 'message' => 'Not authenticated']);
            return;
        }

        $decoded = $this->jwtService->validateToken($token);

        if ($decoded) {
            echo json_encode(['success' => true, 'user' => $decoded]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid token']);
        }
    }
}
