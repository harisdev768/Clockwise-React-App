<?php
// Modules/ForgotPassword-2/Controllers/ResetPasswordController.php

namespace App\Modules\ForgotPassword\Controllers;

use App\Core\Http\Request;

use App\Modules\ForgotPassword\Services\ResetPasswordService;

class ResetPasswordController {
    private ResetPasswordService $service;

    public function __construct(ResetPasswordService $service) {
        $this->service = $service;
    }

    public function handleRequest() {
        $request = new Request();
        $body = $request->getBody();

        $token = trim($body['token'] ?? '');
        $newPassword = trim($body['new_password'] ?? '');

        if (!$token || !$newPassword) {
            echo json_encode(['success' => false, 'message' => 'Token and new password are required.']);
            return;
        }

        $this->service->resetPassword($token, $newPassword);
    }
}
