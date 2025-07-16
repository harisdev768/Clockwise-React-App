<?php
namespace App\Modules\ForgotPassword\Factories;

use App\Config\Container;
use App\Modules\ForgotPassword\Controllers\ForgotPasswordController;

class ForgotPasswordFactory{

    private Container $container;
    public function __construct(Container $container){
        $this->container = $container;
    }

    public function handler($data){
        $controller = $this->container->get(ForgotPasswordController::class);
        $controller->handleRequest($data);
    }

}