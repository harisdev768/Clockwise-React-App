<?php
// Modules/ForgotPassword-2/Services/ForgotPasswordService.php

namespace App\Modules\ForgotPassword\Services;

use App\Modules\Login\Models\Mappers\UserMapper;
use App\Modules\ForgotPassword\Mappers\PasswordResetMapper;
use App\Modules\ForgotPassword\Utilities\Mailer;
use DateTime;

class ForgotPasswordService {
    private UserMapper $userMapper;
    private PasswordResetMapper $resetMapper;
    private Mailer $mailer;

    public function __construct(UserMapper $userMapper, PasswordResetMapper $resetMapper, Mailer $mailer) {
        $this->userMapper = $userMapper;
        $this->resetMapper = $resetMapper;
        $this->mailer = $mailer;
    }

    public function sendResetEmail(string $email): void {
        $user = $this->userMapper->findByEmail($email);

        if (!$user) {
            echo json_encode(['success' => true, 'message' => "If your email is registered, you'll receive a reset link."]);
            return;
        }

        $token = bin2hex(random_bytes(32));
        $expiresAt = (new DateTime('+1 hour'))->format('Y-m-d H:i:s');
        $this->resetMapper->storeToken($email, $token, $expiresAt);

        $this->mailer->sendResetLink($email, $token);

        echo json_encode(['success' => true, 'message' => 'Reset link has been emailed.']);
    }
}