<?php
namespace App\Modules\ForgotPassword\UseCases;

use App\Modules\ForgotPassword\Request\ResetPasswordRequest;
use App\Modules\ForgotPassword\Services\ResetPasswordService;

class ResetUseCase
{
    private ResetPasswordService $resetPasswordService;

    public function __construct(ResetPasswordService $resetPasswordService)
    {
        $this->resetPasswordService = $resetPasswordService;
    }

    public function execute(ResetPasswordRequest $request)
    {
        $token = $request->getToken();
        $newPassword = $request->getNewPassword();

        $this->resetPasswordService->resetPassword($token,$newPassword);
    }
}
