<?php
namespace App\Modules\ForgotPassword\Factories;

use App\Config\Container;
use App\Modules\ForgotPassword\Controllers\ResetPasswordController;

class ResetPasswordFactory{

    private Container $container;

    public function __construct(Container $container){
        $this->container = $container;
    }

    public function handler()
    {
        $controller = $this->container->get(ResetPasswordController::class);
        $controller->handleRequest();
    }
}