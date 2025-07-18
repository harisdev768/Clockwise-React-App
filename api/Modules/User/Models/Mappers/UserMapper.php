<?php
namespace App\Modules\User\Models\Mappers;

use App\Modules\User\Models\User;

class UserMapper
{
    public function toDatabase(User $user): array
    {
        return [
            $user->getFirstName(),
            $user->getLastName(),
            $user->getEmail(),
            $user->getUsername(),
            $user->getPasswordHash(),
        ];
    }
}
