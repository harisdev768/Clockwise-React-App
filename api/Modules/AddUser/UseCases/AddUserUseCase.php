<?php
namespace App\Modules\AddUser\UseCases;

class AddUserUseCase{

    public function execute(AddUserRequest $request): User {
        // Hash default password (or generate random)
        $hashedPassword = password_hash("default123", PASSWORD_BCRYPT);

        $user = new User(
            null, // id auto
            $request->getFullName(),
            $request->getUsername(),
            $request->getEmail(),
            $hashedPassword,
            $request->getRole()
        );
        return $this->userMapper->insert($user);
    }

}