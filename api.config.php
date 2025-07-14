<?php

// MIDDLEWARE / Middleware

use App\Config\Container;
use App\Core\Http\Response;
use App\Modules\Login\Factories\LoginFactory;
use App\Modules\Login\Factories\JWTFactory;
use App\Modules\ForgotPassword\Factories\ForgotPasswordFactory;
use App\Modules\ForgotPassword\Factories\ResetPasswordFactory;
use App\Modules\Login\Services\JWTService;
use App\Modules\Login\Models\Mappers\UserMapper;
use App\Modules\Login\Models\Mappers\UserTokenMapper;

// Requests
use App\Modules\Login\Requests\LoginRequest;
use App\Core\Http\Request;

// Exceptions
use App\Core\Exceptions\ApiException;

// Container setup
$container ??= Container::getInstance();

$container->bind(JWTService::class, fn() => new JWTService());
$container->bind(LoginFactory::class, fn() => new LoginFactory($container));
$container->bind(ForgotPasswordFactory::class, fn() => new ForgotPasswordFactory($container));
$container->bind(ResetPasswordFactory::class, fn() => new ResetPasswordFactory($container));
$container->bind(JWTFactory::class, fn() => new JWTFactory($container));
$container->bind(UserMapper::class, fn() => new UserMapper($container->get(PDO::class)));
$container->bind(UserTokenMapper::class, new UserTokenMapper($container->get(PDO::class)));
$container->bind(LoginRequest::class, fn() => new Request());
$container->bind(ApiException::class, fn() => new ApiException());

// Middleware-style functions

function handleLogin()
{
    $request = Container::getInstance()->get(Request::class);
    $data = $request->all();

    if (empty($data)) {
        throw new ApiException("User object not found", 404);
    }

    if (empty($data['email']) || empty($data['password'])) {
        throw new ApiException("Email and password are required", 422);
    }

    Container::getInstance()->get(LoginFactory::class)->handleRequest($data);
}

function handleLogout()
{
    setcookie('jwt', '', time() - 3600, '/', '', true, true);
    return Response::logout();
}

function handleMe()
{
    Container::getInstance()->get(JWTFactory::class)->handleJWT();
}

function handleForgotPassword()
{
    $request = Container::getInstance()->get(Request::class);
    $data = $request->all();

    if (empty($data['email'])) {
        throw new ApiException("Email is required", 422);
    }

    Container::getInstance()->get(ForgotPasswordFactory::class)->handler();
}

function handleResetPassword()
{
    $request = Container::getInstance()->get(Request::class);
    $data = $request->all();

    if (empty($data['token']) || empty($data['new_password'])) {
        throw new ApiException("Token and new password are required", 422);
    }

    Container::getInstance()->get(ResetPasswordFactory::class)->handler();
}
