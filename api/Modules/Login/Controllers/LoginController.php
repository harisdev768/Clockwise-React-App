<?php
namespace App\Modules\Login\Controllers;

use App\Core\Http\Response;
use App\Modules\Login\UseCases\LoginUseCase;
use App\Modules\Login\Requests\LoginRequest;
use App\Modules\Login\Response\LoginResponse;

class LoginController {
    private LoginUseCase $loginUseCase;
    private LoginRequest $request;

    public function __construct(LoginUseCase $loginUseCase, LoginRequest $request) {
        $this->loginUseCase = $loginUseCase;
        $this->request = $request;
    }

    public function login() {

        $email = $this->request->getEmail();
        $username = $this->request->getUsername();
        $password = $this->request->getPassword();

        if ((empty($email) && empty($username)) || empty($password)) {
            return Response::unauthorized('Email or username and password are required');
        }


        $response = $this->loginUseCase->execute($email, $username, $password);

        // Use loging resoponse success with response
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
            return LoginResponse::success([
                    'user_id' => $response['user_id'],
                    'token' => $response['token'] ?? null
                ]);
        }else{
            return Response::unauthorized('Invalid email or password');
        }
    }

}
