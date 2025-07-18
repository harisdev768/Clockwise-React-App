<?php
namespace App\Modules\User\Factories;

use App\Config\Container;
use App\Config\DB;
use App\Modules\User\Models\Hydrators\UserHydrator;
use App\Modules\User\Models\Mappers\UserMapper;
use App\Modules\User\Services\UserService;
use App\Modules\User\UseCases\AddUserUseCase;
use App\Modules\User\Controllers\AddUserController;

class AddUserFactory
{
    private Container $container;

    public function __construct(Container $container) {
        $this->container = $container;
    }

    public function handleRequest($data){

    }

    public static function create(): AddUserController
    {
        $pdo = DB::getConnection();
        $hydrator = new UserHydrator();
        $mapper = new UserMapper();
        $service = new UserService($pdo, $hydrator, $mapper);
        $useCase = new AddUserUseCase($service);
        return new AddUserController($useCase);
    }
}
