<?php
namespace App\Modules\Login\UseCases;

use App\Core\Http\Response;
use App\Modules\Login\Services\JWTService;
use App\Modules\Login\Services\LoginUserService;
use App\Modules\Login\Models\Mappers\UserTokenMapper;


class LoginUseCase {
    private LoginUserService $userService;
    private JWTService $jwtService;
    private UserTokenMapper $tokenMapper;

    public function __construct(LoginUserService $userService, JWTService $jwtService , UserTokenMapper $tokenMapper) {
        $this->userService = $userService;
        $this->jwtService = $jwtService;
        $this->tokenMapper = $tokenMapper;
    }

    public function execute(string $email, string $username , string $password): array {
        $identifier = $email ?? $username ?? null;

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
                'token' => $token ?? ''
            ];
        }

        return [
            'success' => false,
            'message' => 'Invalid credentials'
        ];
    }
}
