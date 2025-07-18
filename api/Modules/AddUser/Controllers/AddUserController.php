<?php
namespace App\Modules\AddUser\Controllers;

class AddUserController{

    public function handle(array $request): Response {
        try {
            $addUserRequest = new AddUserRequest($request);
            $user = $this->service->addUser($addUserRequest);
            return AddUserResponse::success($user);
        } catch (\Exception $e) {
            return AddUserResponse::error($e->getMessage());
        }
    }


}