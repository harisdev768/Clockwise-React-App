<?php
namespace App\Modules\Login\Models;

use App\Modules\Login\Models\UserID;
use App\Modules\Login\Models\UserRole;

class User {
    private ?UserID $userID = null;
    private string $firstName;
    private string $lastName;
    private string $identifier = '';
    private string $email = '';    // Initialize with an empty string
    private string $password = ''; // Initialize with an empty string
    private string $username = ''; // Initialize with an empty string
    private int $roleId;
    private string $createdAt;
    private ?UserRole $role = null;

    public function setIdentifier(string $identifier): void
    {
        $this->identifier = $identifier;
    }

    public function getIdentifier(): string{
        return $this->identifier;
    }

    public function userExists(): bool{ //
        if (empty($this->userID) || is_null($this->userID)){
            return false;
        }else{
            return true;
        }
    }

    public function getUserID(): ?UserID{
        return $this->userID;
    }

    public function setUserID(UserID $userID): void{
        $this->userID = $userID;
    }

    public function getFirstName(): string { return $this->firstName; }
    public function setFirstName(string $firstName): void { $this->firstName = $firstName; }

    public function getLastName(): string { return $this->lastName; }
    public function setLastName(string $lastName): void { $this->lastName = $lastName; }

    public function getEmail(): string { return $this->email; }
    public function setEmail(string $email): void { $this->email = $email; }

    public function getUsername(): string { return $this->username; }
    public function setUsername($username): void { $this->username = $username; }

    public function getPassword(): string { return $this->password; }
    public function setPassword(string $password): void { $this->password = $password; }

    public function getRoleId(): int { return $this->roleId; }
    public function setRoleId(int $roleId): void { $this->roleId = $roleId; }

    public function getCreatedAt(): string { return $this->createdAt; }
    public function setCreatedAt(string $createdAt): void { $this->createdAt = $createdAt; }

    public function getRole(): ?UserRole { return $this->role; }
    public function setRole(UserRole $role): void { $this->role = $role; }
}