<?php

// api/modules/login/controllers/LoginController.php
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

    public function login(): array {
        $data = $this->request->all();
        return $this->loginUser->execute($data);
    }
}
