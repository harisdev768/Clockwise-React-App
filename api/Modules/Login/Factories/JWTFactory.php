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


    public function handleJWT()
    {
        $controller = $this->container->get(JWTController::class);
        $controller->authenticate(); //validate call to controller


    }
}