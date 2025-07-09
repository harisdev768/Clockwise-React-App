<?php

namespace App\Modules\ForgotPassword\Controllers;

use App\Core\Http\Request;
use App\Modules\ForgotPassword\Services\ForgotPasswordService;


class ForgotPasswordController {
    private ForgotPasswordService $service;

    public function __construct(ForgotPasswordService $service) {
        $this->service = $service;
    }

    public function handleRequest() {
        $request = new Request();
        $body = $request->getBody();
        $email = trim($body['email'] ?? '');

        if (empty($email)) {
            echo json_encode(['success' => false, 'message' => 'Email is required.']);
            return;
        }

        $this->service->sendResetEmail($email);
    }
}
