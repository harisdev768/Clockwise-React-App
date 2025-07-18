<?php
namespace App\Modules\User\Models;

class User
{
    private string $firstName;
    private string $lastName;
    private string $email;
    private string $username;
    private string $passwordHash;

    public function setFirstName(string $name): void { $this->firstName = $name; }
    public function setLastName(string $name): void { $this->lastName = $name; }
    public function setEmail(string $email): void { $this->email = $email; }
    public function setUsername(string $username): void { $this->username = $username; }
    public function setPasswordHash(string $hash): void { $this->passwordHash = $hash; }

    public function getFirstName(): string { return $this->firstName; }
    public function getLastName(): string { return $this->lastName; }
    public function getEmail(): string { return $this->email; }
    public function getUsername(): string { return $this->username; }
    public function getPasswordHash(): string { return $this->passwordHash; }
}
