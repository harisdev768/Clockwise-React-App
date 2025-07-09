<?php
namespace App\Modules\Login\Factories;

use App\Config\Container;
use App\Modules\Login\Controllers\LoginController;


class LoginFactory {
    private Container $container;

    public function __construct(Container $container) {
        $this->container = $container;
    }

    public function handleRequest(): void {
        header('Content-Type: application/json');
            $controller = $this->container->get(LoginController::class);
            $response = $controller->login();
            echo json_encode($response);
            exit;

    }
}
