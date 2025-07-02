<?php

use App\Config\Container;
use App\Config\DB;
use App\Core\Http\Request;
use App\Modules\Login\Controllers\LoginController;
use App\Modules\Login\Models\Mappers\UserMapper;
use App\Modules\Login\Models\Mappers\UserMapperInterface;

require_once __DIR__ . '/vendor/autoload.php';

$container = Container::getInstance();

// Bind required dependencies
$container->bind(\PDO::class, DB::getConnection());
$container->bind(Request::class, new Request());

// Parse URI and Method
$uri = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

// Route to login
if ($uri === '/login' && $method === 'POST') {
    $loginController = $container->get(LoginController::class);
    $response = $loginController->login();

    echo json_encode($response);
    exit;
}

// Fallback
http_response_code(405);
echo json_encode(['success' => false, 'message' => 'Method Not Allowed']);
