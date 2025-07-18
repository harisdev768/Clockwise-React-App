<?php
 namespace App\Modules\User\Models\Hydrators;

 use App\Modules\User\Request\AddUserRequest;
 use App\Modules\User\Models\User;


 class UserHydrator
 {
     public function fromRequest(AddUserRequest $request): User
     {
         $user = new User();
         $user->setFirstName($request->firstName);
         $user->setLastName($request->lastName);
         $user->setEmail($request->email);
         $user->setUsername($request->username);
         $user->setPasswordHash(password_hash($request->password, PASSWORD_BCRYPT));
         return $user;
     }
 }
