<?php
namespace App\Modules\User\UseCases;

use App\Modules\User\Services\UserService;
use App\Modules\User\Request\AddUserRequest;

class AddUserUseCase
{
    private UserService $service;

    public function __construct(UserService $service)
    {
        $this->service = $service;
    }

    public function execute(AddUserRequest $request): void
    {
        $this->service->createUser($request);
    }
}
