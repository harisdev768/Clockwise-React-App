<?php

namespace App\Modules\Login\Models\Mappers;

class UserTokenMapper {
    private \PDO $pdo;

    public function __construct(\PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function saveToken(int $userId, string $token, int $expirySeconds = 3600): void {
        $issuedAt = date('Y-m-d H:i:s');
        $expiresAt = date('Y-m-d H:i:s', time() + $expirySeconds);

        // Check if token already exists for this user
        $stmt = $this->pdo->prepare("SELECT token_id FROM user_tokens WHERE user_id = ?");
        $stmt->execute([$userId]);
        $existing = $stmt->fetch();

        if ($existing) {
            // Update the existing token
            $updateStmt = $this->pdo->prepare("
                UPDATE user_tokens 
                SET jwt_token = ?, issued_at = ?, expires_at = ?
                WHERE user_id = ?
            ");
            $updateStmt->execute([$token, $issuedAt, $expiresAt, $userId]);
        } else {
            // Insert new token
            $insertStmt = $this->pdo->prepare("
                INSERT INTO user_tokens (user_id, jwt_token, issued_at, expires_at)
                VALUES (?, ?, ?, ?)
            ");
            $insertStmt->execute([$userId, $token, $issuedAt, $expiresAt]);
        }
    }
}
