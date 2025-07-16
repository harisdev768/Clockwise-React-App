<?php
namespace App\Modules\Login\Controllers;

use App\Modules\Login\UseCases\LoginUseCase;
use App\Modules\Login\Requests\LoginRequest;

class LoginController {
    private LoginUseCase $loginUseCase;

    public function __construct(LoginUseCase $loginUseCase) {
        $this->loginUseCase = $loginUseCase;
    }

    public function login(array $data) {
         $this->loginUseCase->execute(new LoginRequest($data['email'], $data['password']));
    }

}
