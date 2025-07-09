<?php
use App\Config\Container;
use App\Config\DB;
use App\Core\Http\Request;
use App\Core\Http\Router;

use App\Modules\Login\Factories\LoginFactory;
use App\Modules\Login\Services\JWTService;
use App\Modules\Login\Models\Mappers\UserTokenMapper;
use App\Modules\Login\Models\Mappers\UserMapper;

use App\Modules\ForgotPassword\Controllers\ForgotPasswordController;
use App\Modules\ForgotPassword\Controllers\ResetPasswordController;

require_once __DIR__ . '/vendor/autoload.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Headers
header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Container bindings
$container = Container::getInstance();
$container->bind(\PDO::class, DB::getConnection());
$container->bind(Request::class, new Request());
$container->bind(JWTService::class, fn() => new JWTService());
$container->bind(LoginFactory::class, fn() => new LoginFactory($container));
$container->bind(UserTokenMapper::class, new UserTokenMapper(DB::getConnection()));
$container->bind(UserMapper::class, fn() => new UserMapper(DB::getConnection()));
$container->bind(ForgotPasswordFactory::class, fn() => new ForgotPasswordFactory(new Request()));

// Create and register routes
$router = new Router();

$router->add('POST', '/login', function () use ($container) {
    $factory = $container->get(LoginFactory::class);
    $factory->handleRequest();
});

$router->add('POST', '/logout', function () {
    setcookie('jwt', '', time() - 3600, '/', '', true, true);
    echo json_encode(['success' => true, 'message' => 'Logged out']);
});

$router->add('GET', '/me', function () use ($container) {
    $token = $_COOKIE['jwt'] ?? null;
    if (!$token) {
        echo json_encode(['success' => false, 'message' => 'Not authenticated']);
        return;
    }

    $jwtService = $container->get(JWTService::class);
    $decoded = $jwtService->validateToken($token);

    echo json_encode([
        'success' => $decoded ? true : false,
        'message' => $decoded ? 'Authenticated' : 'Invalid token',
        'user' => $decoded
    ]);
});

$router->add('POST', '/forgot-password', function () use ($container) {
    $controller = $container->get(ForgotPasswordController::class);
    $controller->handleRequest();
});

$router->add('POST', '/reset-password', function () use ($container) {
    $controller = $container->get(ResetPasswordController::class);
    $controller->handleRequest();
});

// Run dispatcher
$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
