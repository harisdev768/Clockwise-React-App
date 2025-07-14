<?php
namespace App\Modules\Login\UseCases;


use App\Modules\Login\Requests\LoginRequest;
use App\Modules\Login\Response\LoginResponse;
use App\Modules\Login\Services\JWTService;
use App\Modules\Login\Services\LoginUserService;
use App\Modules\Login\Models\Mappers\UserTokenMapper;

use App\Modules\Login\Exceptions\LoginException;

class LoginUseCase {
    private LoginUserService $userService;
    private JWTService $jwtService;
    private UserTokenMapper $tokenMapper;

    public function __construct(LoginUserService $userService, JWTService $jwtService , UserTokenMapper $tokenMapper) {
        $this->userService = $userService;
        $this->jwtService = $jwtService;
        $this->tokenMapper = $tokenMapper;
    }

    public function execute(LoginRequest $request): array {


        $identifier = $request->getEmail();
        $password = $request->getPassword();

        $user = $this->userService->login($identifier,$password);

        if ($user) {
            $payload = [
                'name' => $user->getFirstName().' '.$user->getLastName(),
                'user_id' => $user->getId(),
                'email' => $user->getEmail(),
                'role' => $user->getRole()
            ];

            $token = $this->jwtService->generateToken($payload);
            $this->tokenMapper->saveToken($user->getId(), $token);

            return LoginResponse::success($user->getId(),"Login successful!" , $token );
        }

        throw LoginException::unauthorized();

    }
}
