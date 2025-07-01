<?php

namespace App\Modules\Login\Controllers;

use App\Modules\Login\UseCases\LoginUser;

class UserController {
    private LoginUser $loginUser;

    public function __construct(LoginUser $loginUser) {
        $this->loginUser = $loginUser;
    }


    public static function create(LoginUser $loginUseCase): self {
        return new self($loginUseCase);
    }


    public function login(): void {
        $data = json_decode(file_get_contents("php://input"), true);
        $response = $this->loginUser->execute($data);
        header('Content-Type: application/json');
        echo json_encode($response);

        print_r($response);
    }
}