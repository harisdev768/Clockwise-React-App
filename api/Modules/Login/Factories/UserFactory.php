<?php

namespace App\Modules\Login\Factories;

use App\Config\DB;
use App\Modules\Login\Models\Mappers\UserMapper;
use App\Modules\Login\Services\UserService;
use App\Modules\Login\UseCases\LoginUser;
use App\Modules\Login\Controllers\UserController;

class UserFactory {
    private UserController $userController;

    //public function __construct( Private UserMapper::class $userMapper  ) {
    public function __construct( ) {
        $pdo = DB::getConnection();

        $userMapper = UserMapper::create($pdo);
        $userService = UserService::create($userMapper);
        $loginUseCase = LoginUser::create($userService);
        $this->userController = UserController::create($loginUseCase);
    }


    public function handleRequest(string $uri, string $method): void {
        try {
            $parsedUri = parse_url($uri, PHP_URL_PATH);

            echo $parsedUri;

            if ($parsedUri === '/api-2/' && $method === 'POST') {
                $this->userController->login();
                header('Content-Type: application/json');
                echo json_encode($this->userController->login(), JSON_PRETTY_PRINT);

            } else {
                http_response_code(404);
                echo json_encode(['message' => 'Not Found']);
            }
        } catch (\Throwable $e) {
            http_response_code(500);
            echo json_encode([
                'message' => 'An error occurred',
                'error' => $e->getMessage()
            ]);
        }
    }

}
