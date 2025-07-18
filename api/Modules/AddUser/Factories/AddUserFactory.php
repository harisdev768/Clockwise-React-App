<?php
namespace App\Modules\AddUser\Factories;

use App\Modules\AddUser\Controllers\AddUserController;
use App\Modules\AddUser\Services\AddUserService;
use App\Modules\AddUser\UseCases\AddUserUseCase;
use App\Modules\Login\Models\Hydrators\UserHydrator;
use App\Modules\AddUser\Models\Mappers\UserMapper;
use App\Config\DatabaseConnection;

class AddUserControllerFactory {
public static function create(): AddUserController {
$pdo = DatabaseConnection::getInstance()->getConnection();
$userMapper = new UserMapper($pdo);
$hydrator = new UserHydrator();

$useCase = new AddUserUseCase($userMapper, $hydrator);
$service = new AddUserService($useCase);

return new AddUserController($service);
}
}
