<?php

namespace App\Modules\ForgotPassword\Controllers;


use App\Modules\ForgotPassword\Request\ForgotPasswordRequest;
use App\Modules\ForgotPassword\Services\ForgotPasswordService;
use App\Modules\ForgotPassword\Response\ForgotPasswordResponse;

class ForgotPasswordController {
    private ForgotPasswordService $service;

    public function __construct(ForgotPasswordService $service) {
        $this->service = $service;
    }

    public function handleRequest() {

        $request = new ForgotPasswordRequest();

        $email = trim(($request->getEmail()) ?? '');

        if (empty($email)) {
            return ForgotPasswordResponse::unauthorized("Email is required.");
        }

        $this->service->sendResetEmail($email);
    }
}
