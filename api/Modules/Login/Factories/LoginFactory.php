<?php
namespace App\Modules\Login\Factories;

use App\Config\Container;
use App\Modules\Login\Controllers\LoginController;
use App\Modules\Login\Requests\LoginRequest;


class LoginFactory {
    private Container $container;

    public function __construct(Container $container) {
        $this->container = $container;
    }
    
    public function handleRequest($data){
        $controller = $this->container->get(LoginController::class);
        $controller->login($data);
    }
}
