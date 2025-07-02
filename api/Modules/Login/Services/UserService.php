<?php
namespace App\Modules\Login\Services;

use App\Modules\Login\Models\Mappers\UserMapper;
use App\Modules\Login\Models\User;

class UserService {
    private UserMapper $userMapper;

    public function __construct(UserMapper $userMapper) {
        $this->userMapper = $userMapper;
    }

    public function login(string $email, string $password): ?User {
        $user = $this->userMapper->findByEmail($email);

        if (!$user) {
            return null;
        }

        if (password_verify($password, $user->getPassword())) {
            return $user;
        }

        return null;
    }

}