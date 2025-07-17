<?php

namespace App\Modules\Login\Models;

class UserRole {

    private int $roleId;
    private string $roleName;

    public function __construct(int $roleId){
        $this->roleId = $roleId;
        if($roleId == 1) {
            $this->roleName = "Manager";
        } elseif($roleId == 2) {
            $this->roleName = "Employee";
        }
    }
    public function getRoleId(): int {
        return $this->roleId;
    }
    public function getRoleName(): string { return $this->roleName; }
}