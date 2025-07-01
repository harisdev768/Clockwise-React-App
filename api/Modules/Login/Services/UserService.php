<?php
namespace App\Modules\Login\Services;

use App\Modules\Login\Models\Mappers\UserMapper;
use App\Modules\Login\Models\User;

class UserService {
    private UserMapper $userMapper;

    public function __construct(UserMapper $userMapper) {
        $this->userMapper = $userMapper;
    }

    public static function create(UserMapper $userMapper): self {
        return new self($userMapper);
    }

    public function login(string $email, string $password): ? User {
//        echo PHP_EOL.$email .PHP_EOL. $password .PHP_EOL;
        $user = $this->userMapper->findByEmail($email);
//        echo PHP_EOL. $user->getPassword().' - - - - '.PHP_EOL;
        if($user){
//            echo PHP_EOL ."User True" . PHP_EOL ;
        }
        if(password_verify($password, $user->getPassword()) || $password === $user->getPassword() ){
//            echo PHP_EOL . "Password True".PHP_EOL;
        }else{
//            echo PHP_EOL . "Password False".PHP_EOL;
        }

        if ($user && password_verify($password, $user->getPassword())) {
//echo PHP_EOL.PHP_EOL.PHP_EOL . "<br>Password True<br>".PHP_EOL.PHP_EOL.PHP_EOL;
//            echo $password, PHP_EOL;
//            echo $user->getPassword(), PHP_EOL;
//            echo PHP_EOL . "<br>Password True<br>".PHP_EOL;


//        if ($user && $password === $user->getPassword()) {
//            echo PHP_EOL.$email .PHP_EOL. $password .PHP_EOL;
            return $user;
        }
        return null;
    }
}