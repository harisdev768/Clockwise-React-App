<?php
namespace App\Modules\Login\Services;

use App\Modules\Login\Models\Mappers\UserMapper;
use App\Modules\Login\Models\User;
use App\Modules\Login\Exceptions\LoginException;

class LoginUserService {

    private UserMapper $userMapper;

    public function __construct(UserMapper $userMapper) {
        $this->userMapper = $userMapper;
    }

    public function login(User $user): ?User {

        $password = $user->getPassword();
        $identifier = $user->getIdentifier();

        $userRes = $this->userMapper->findByIdentifier($identifier);

        if (!$userRes->userExist() || !password_verify($password, $userRes->getPassword())) {
            throw LoginException::unauthorized();
        }

        return $userRes;
    }


}