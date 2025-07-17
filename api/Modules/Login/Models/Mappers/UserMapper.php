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
    public function findByIdentifier(User $user): ?User {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = ? OR username = ?");
        $stmt->execute([$user->getIdentifier(), $user->getIdentifier()]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ? UserHydrator::hydrate($row) : $user ;

    }


    public function findByEmail(User $user): ?User {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$user->getEmail()]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? UserHydrator::hydrate($row) : $user ;

    }
    public function findByUserName(User $user): ?User {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$user->getUsername()]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? UserHydrator::hydrate($row) : $user ;

    }
    public function updatePasswordByEmail(string $email, string $hashedPassword): void {
        $stmt = $this->pdo->prepare("UPDATE users SET password_hash = ? WHERE email = ?");
        $stmt->execute([$hashedPassword, $email]);
    }

}
