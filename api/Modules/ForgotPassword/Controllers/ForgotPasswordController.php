<?php

namespace App\Modules\ForgotPassword\Controllers;


use App\Modules\ForgotPassword\Request\ForgotPasswordRequest;
use App\Modules\ForgotPassword\UseCases\ForgotUseCase;

class ForgotPasswordController {

    private ForgotUseCase $forgotUseCase;


    public function __construct(ForgotUseCase $forgotUseCase) {
        $this->forgotUseCase = $forgotUseCase;
    }

    public function handleRequest($data) {

        $this->forgotUseCase->execute(new ForgotPasswordRequest($data['email']));

    }
}
