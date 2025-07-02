<?php
namespace App\Modules\Login\UseCases;

use App\Modules\Login\Services\JWTService;
use App\Modules\Login\Services\UserService;


class LoginUser {
    private UserService $userService;
    private JWTService $jwtService;

    public function __construct(UserService $userService, JWTService $jwtService) {
        $this->userService = $userService;
        $this->jwtService = $jwtService;
        
    }

    public function execute(array $data): array {
        $identifier = $data['email'] ?? $data['username'] ?? null;
        $password = $data['password'] ?? null;

        if (!$identifier || !$password) {
            return [
                'success' => false,
                'message' => 'Email/username and password are required'
            ];
        }

        $user = $this->userService->login($identifier, $password);

        if ($user) {
            $payload = [
                'user_id' => $user->getId(),
                'email' => $user->getEmail(),
                'role' => $user->getRole()
            ];

            $token = $this->jwtService->generateToken($payload);

            return [
                'success' => true,
                'message' => 'Login successfulsss',
                'user_id' => $user->getId(),
                'token' => $token
            ];
        }

        return [
            'success' => false,
            'message' => 'Invalid credentials'
        ];
    }
}
