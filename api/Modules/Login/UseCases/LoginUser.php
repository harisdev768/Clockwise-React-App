<?php
namespace App\Modules\Login\UseCases;

use App\Modules\Login\Services\JWTService;
use App\Modules\Login\Services\UserService;
use App\Modules\Login\Models\Mappers\UserTokenMapper;


class LoginUser {
    private UserService $userService;
    private JWTService $jwtService;
    private UserTokenMapper $tokenMapper;

    public function __construct(UserService $userService, JWTService $jwtService , UserTokenMapper $tokenMapper) {
        $this->userService = $userService;
        $this->jwtService = $jwtService;
        $this->tokenMapper = $tokenMapper;
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
                'name' => $user->getFirstName().' '.$user->getLastName(),
                'user_id' => $user->getId(),
                'email' => $user->getEmail(),
                'role' => $user->getRole()
            ];

            $token = $this->jwtService->generateToken($payload);
            $this->tokenMapper->saveToken($user->getId(), $token);

            return [
                'success' => true,
                'message' => 'Login successful!',
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
