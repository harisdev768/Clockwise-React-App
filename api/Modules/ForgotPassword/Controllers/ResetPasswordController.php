<?php
namespace App\Modules\ForgotPassword\Controllers;


use App\Core\Http\Request;
use App\Modules\ForgotPassword\Request\ResetPasswordRequest;
use App\Modules\ForgotPassword\Services\ResetPasswordService;
use App\Modules\ForgotPassword\Response\ResetPasswordResponse;

class ResetPasswordController {
    private ResetPasswordService $service;

    public function __construct(ResetPasswordService $service) {
        $this->service = $service;
    }

    public function handleRequest() {

        $request = new ResetPasswordRequest();

        $token = trim(($request->getToken()) ?? '');
        $newPassword = trim(($request->getNewPassword()) ?? '');

        if (!$token || !$newPassword) {
            return ResetPasswordResponse::inputRequired("Token and new password are required.");
        }

        $this->service->resetPassword($token, $newPassword);
    }
}
