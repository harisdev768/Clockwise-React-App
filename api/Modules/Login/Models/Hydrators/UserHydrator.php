<?php
namespace App\Modules\Login\Models\Hydrators;

use App\Modules\Login\Models\User;
use App\Modules\Login\Models\UserID;
use App\Modules\Login\Models\UserRole;

class UserHydrator {
    public static function hydrate(array $row): User {
        $user = new User();
        $user->setUserID(new UserID($row['id']));
        $user->setFirstName($row['first_name']);
        $user->setLastName($row['last_name']);
        $user->setEmail($row['email']);
        $user->setUsername($row['username']);
        $user->setPassword($row['password_hash']);
        $user->setRoleId($row['role_id']);
        $user->setRole( new UserRole( $row['role_id']) );
        $user->setCreatedAt($row['created_at']);
        return $user;
    }
}