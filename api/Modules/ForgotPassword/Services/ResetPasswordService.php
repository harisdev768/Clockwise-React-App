<?php
// Modules/ForgotPassword-2/Services/ResetPasswordService.php

namespace App\Modules\ForgotPassword\Services;

use App\Modules\Login\Models\Mappers\UserMapper;
use App\Modules\ForgotPassword\Mappers\PasswordResetMapper;
use DateTime;

class ResetPasswordService {
    private UserMapper $userMapper;
    private PasswordResetMapper $resetMapper;

    public function __construct(UserMapper $userMapper, PasswordResetMapper $resetMapper) {
        $this->userMapper = $userMapper;
        $this->resetMapper = $resetMapper;
    }

    public function resetPassword(string $token, string $newPassword): void {
        $reset = $this->resetMapper->findByToken($token);

        if (!$reset || new DateTime() > new DateTime($reset['expires_at'])) {
            echo json_encode(['success' => false, 'message' => 'Token is invalid or expired.']);
            return;
        }

        $hashed = password_hash($newPassword, PASSWORD_BCRYPT);
        $this->userMapper->updatePasswordByEmail($reset['email'], $hashed);
        $this->resetMapper->deleteToken($token);

        echo json_encode(['success' => true, 'message' => 'Password reset successful.']);
    }
}