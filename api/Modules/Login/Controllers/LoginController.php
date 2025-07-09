<?php
namespace App\Modules\Login\Controllers;

use App\Modules\Login\UseCases\LoginUser;
use App\Core\Http\Request;

class LoginController {
    private LoginUser $loginUser;
    private Request $request;

    public function __construct(LoginUser $loginUser, Request $request) {
        $this->loginUser = $loginUser;
        $this->request = $request;
    }

    public function login(): void {
        $data = $this->request->all();

        if ((!isset($data['email']) && !isset($data['username'])) || !isset($data['password'])) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'Email/username and password are required'
            ]);
            exit;
        }

        $response = $this->loginUser->execute($data);


        if($response['success'] && isset($response['token']) ) {
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
        }

    //        echo json_encode(['success' => true, 'message' => 'Logged in']);


        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }


    public function tauth(): void {

    }
}
