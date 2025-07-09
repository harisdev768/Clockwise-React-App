<?php
namespace App\Modules\Login\Services;

use App\Modules\Login\Models\Mappers\UserMapper;
use App\Modules\Login\Models\User;

class UserService {
    private UserMapper $userMapper;

    public function __construct(UserMapper $userMapper) {
        $this->userMapper = $userMapper;
    }

    public function login(string $identifier, string $password): ?User {
        $user = $this->userMapper->findByEmail($identifier);
        if (!$user) {
            $user = $this->userMapper->findByUserName($identifier);
        }

        if (!$user || !password_verify($password, $user->getPassword())) {
            http_response_code(401);
//            echo json_encode(['success'=> false,'message' => 'Invalid email or password']);
            return null;
        }


        if (password_verify($password, $user->getPassword())) {
            return $user;
        }

        return null;
    }


}