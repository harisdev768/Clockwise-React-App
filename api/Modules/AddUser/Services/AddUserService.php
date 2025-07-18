<?php
namespace App\Modules\AddUser\Services;

class AddUserService{
    public function addUser(AddUserRequest $request): User {
        return $this->useCase->execute($request);
    }

}