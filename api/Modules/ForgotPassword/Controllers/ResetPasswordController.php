<?php
namespace App\Modules\ForgotPassword\Controllers;


use App\Modules\ForgotPassword\Request\ResetPasswordRequest;
use App\Modules\ForgotPassword\UseCases\ResetUseCase;

class ResetPasswordController {

    private ResetUseCase $resetUseCase;

    public function __construct(ResetUseCase $resetUseCase) {
        $this->resetUseCase = $resetUseCase;
    }

    public function handleRequest($data) {

        $this->resetUseCase->execute(new ResetPasswordRequest($data['token'], $data['new_password']));

    }
}
