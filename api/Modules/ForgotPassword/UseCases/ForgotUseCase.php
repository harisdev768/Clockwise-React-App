<?php
// Modules/ForgotPassword/UseCases/ForgotUseCase.php

namespace App\Modules\ForgotPassword\UseCases;

use App\Modules\ForgotPassword\Request\ForgotPasswordRequest;
use App\Modules\ForgotPassword\Services\ForgotPasswordService;

class ForgotUseCase
{
    private ForgotPasswordService $forgotPasswordService;

    public function __construct(ForgotPasswordService $forgotPasswordService)
    {
        $this->forgotPasswordService = $forgotPasswordService;
    }

    public function execute(ForgotPasswordRequest $request)
    {
        $this->forgotPasswordService->sendResetEmail($request->getEmail());
    }
}
