<?php

namespace App\Modules\ForgotPassword\Mappers;

use PDO;

class PasswordResetMapper {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function storeToken(string $email, string $token, string $expiresAt): void {
        $stmt = $this->pdo->prepare("INSERT INTO password_resets (email, token, expires_at) VALUES (?, ?, ?)");
        $stmt->execute([$email, $token, $expiresAt]);
    }

    public function findByToken(string $token): ?array {
        $stmt = $this->pdo->prepare("SELECT * FROM password_resets WHERE token = ?");
        $stmt->execute([$token]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function deleteToken(string $token): void {
        $stmt = $this->pdo->prepare("DELETE FROM password_resets WHERE token = ?");
        $stmt->execute([$token]);
    }
}
