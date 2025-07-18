<?php

use App\Core\Http\Response;

// Import the API layer
require_once __DIR__ . '/api.config.php';

$router->add('POST', '/login', fn() => handleLogin());
$router->add('POST', '/logout', fn() => handleLogout());
$router->add('GET', '/me', fn() => handleMe());
$router->add('POST', '/forgot-password', fn() => handleForgotPassword());
$router->add('POST', '/reset-password', fn() => handleResetPassword());
$router->add('POST', '/add-user', fn() => handleAddUser());