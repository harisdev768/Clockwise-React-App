<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


use App\Config\Container;
use App\Modules\Login\UseCases\LoginUser;
use App\Config\DB;
use App\Modules\Login\Controllers\LoginController;
use App\Core\Http\Request;
use App\Modules\Login\Models\Mappers\UserMapper;
use App\Modules\Login\Models\Mappers\UserMapperInterface;



require_once __DIR__ . '/vendor/autoload.php';

// 1. Create container
$container = new Container();

// 2. Manually bind PDO
$pdo = DB::getConnection();
$container->bind(\PDO::class, $pdo);

// 3. Get UseCase (all dependencies will be auto-injected!)
$loginUser = $container->get(LoginUser::class);

// 4. Execute
$response = $loginUser->execute([
    'email' => 'dev@demo.com',
    'password' => '123456789'
]);
//header('Content-Type: application/json');
echo json_encode($response, JSON_PRETTY_PRINT);


$container = Container::getInstance();

//interface UserMapperInterface {  }
//class UserMapper implements UserMapperInterface {  }
$container->bind(\PDO::class, DB::getConnection());
$container->bind(UserMapperInterface::class, UserMapper::class);

//echo "<br>". password_hash('123456789', PASSWORD_DEFAULT);
