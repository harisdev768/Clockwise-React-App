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

        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }



}
