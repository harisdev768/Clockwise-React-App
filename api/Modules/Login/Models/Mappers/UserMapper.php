<?php
namespace App\Modules\Login\Models\Mappers;

use PDO;

use App\Modules\Login\Models\Hydrators\UserHydrator;
use App\Modules\Login\Models\User;

class UserMapper {
    private PDO $pdo;

    public function __construct(\PDO $pdo) {
        $this->pdo = $pdo;
    }
    public function findByEmail(string $email): ?User {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? UserHydrator::hydrate($row) : null;
    }
    public function findByUserName(string $username): ?User {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? UserHydrator::hydrate($row) : null;
    }
    public function updatePasswordByEmail(string $email, string $hashedPassword): void {
        $stmt = $this->pdo->prepare("UPDATE users SET password_hash = ? WHERE email = ?");
        $stmt->execute([$hashedPassword, $email]);
    }

}
