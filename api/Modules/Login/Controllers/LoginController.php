<?php
namespace App\Modules\Login\Controllers;

use App\Core\Http\Response;
use App\Modules\Login\UseCases\LoginUseCase;
use App\Modules\Login\Requests\LoginRequest;
use App\Modules\Login\Response\LoginResponse;


use App\Modules\Login\Exceptions\LoginException;

class LoginController {
    private LoginUseCase $loginUseCase;

    public function __construct(LoginUseCase $loginUseCase) {
        $this->loginUseCase = $loginUseCase;
    }

    public function login(array $data) {
        $response = $this->loginUseCase->execute(new LoginRequest($data['email'], $data['password']));
        if($response['success'] && isset($response['token'])){
            setcookie(
                'jwt',          // Cookie name
                $response['token'],                // JWT token
                [
                    'expires' => time() + 3600,       // 1 hour
                    'path' => '/',
                    'domain' => 'localhost',   //  'clockwise.local' for index.html
                    'secure' => false,               // Set to true if using HTTPS
                    'httponly' => true,
                    'samesite' => 'Lax',           // or 'None' with secure
                ]
            );
            //success response
            return LoginResponse::success([
                    'user_id' => $response['user_id'],
                    'token' => $response['token'] ?? null
                ]);
        }else{
            //return Response::unauthorized('Invalid email or password');
            throw LoginException::missingCredentials();

        }
    }

}
