<?php

require __DIR__ . '/../vendor/autoload.php';

use App\Config\DB;
use App\Modules\Login\Models\Mappers\UserMapper;
use App\Modules\Login\Services\UserService;
use App\Modules\Login\UseCases\LoginUser;

// Mock credentials (assuming you have a test user with this email and correct password hash)
$input = [
    'email' => 'test@example.com',
    'password' => 'plaintext_password'
];

// Dependency Injection (manual for test)
$pdo = DB::getConnection();
$mapper = new UserMapper($pdo);
$service = new UserService($mapper);
$useCase = new LoginUser($service);

// Call it
$response = $useCase->execute($input);

print_r($response);
