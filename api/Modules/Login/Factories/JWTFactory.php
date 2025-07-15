<?php

namespace App\Modules\Login\Factories;

use App\Config\Container;
use App\Modules\Login\Controllers\JWTController;

class JWTFactory
{
    private Container $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }


    public function handleJWT(string $token)
    {
        $controller = $this->container->get(JWTController::class);
        $controller->authenticate($token); //validate call to controller
    }
}