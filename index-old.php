<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require __DIR__ . '/vendor/autoload.php';

use App\Modules\Login\Factories\UserFactory;

$userFactory = new UserFactory();
$userFactory->handleRequest($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
//
//use App\Config\DB;
//use App\Modules\Login\Models\Mappers\UserMapper;
//use App\Modules\Login\Services\UserService;
//use App\Modules\Login\UseCases\LoginUser;
//// Mock credentials (assuming you have a test user with this email and correct password hash)
//$input = [
//    'email' => 'dev@demo.com',
//    'password' => 'loc@lhost'
//];
//
//// Dependency Injection (manual for test)
//$pdo_test = DB::getConnection();
//$mapper = new UserMapper($pdo_test);
//$service = new UserService($mapper);
//$useCase = new LoginUser($service);
//
//// Call it
//$response = $useCase->execute($input);
//
//print_r($response);