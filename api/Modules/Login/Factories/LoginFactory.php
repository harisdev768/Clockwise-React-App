<?php
// api/modules/login/Factories/LoginFactory.php
namespace App\Modules\Login\Factories;

use App\Config\DB;
use App\Modules\Login\Models\Mappers\UserMapper;
use App\Modules\Login\Services\UserService;
use App\Modules\Login\UseCases\LoginUser;

class LoginFactory {
    public static function createLoginUser(): LoginUser {
        $pdo = DB::getConnection(); // Singleton or static PDO

        $userMapper = new UserMapper($pdo);
        $userService = new UserService($userMapper);
        $loginUser = new LoginUser($userService);

        return $loginUser;
    }
}
