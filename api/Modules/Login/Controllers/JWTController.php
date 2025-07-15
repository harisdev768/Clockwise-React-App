<?php
namespace App\Modules\Login\Controllers;

use App\Modules\Login\UseCases\ValidateToken;

class JWTController {
    private ValidateToken $useCase;

    public function __construct(ValidateToken $useCase) {
        $this->useCase = $useCase;
    }

    public function authenticate(string $token){

        $this->useCase->verify($token);

    }
}
