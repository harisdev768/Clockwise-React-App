<?php
namespace App\Modules\User\Request;

class AddUserRequest
{
    public string $firstName;
    public string $lastName;
    public string $email;
    public string $username;
    public string $password;

    public function __construct(array $data)
    {
        if (!isset($data['first_name'], $data['last_name'], $data['email'], $data['username'], $data['password'])) {
            die(json_encode(["success" => false, "message" => "All fields are required."]));
        }

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            die(json_encode(["success" => false, "message" => "Invalid email format."]));
        }

        if (strlen($data['password']) < 6) {
            die(json_encode(["success" => false, "message" => "Password must be at least 6 characters."]));
        }

        $this->firstName = trim($data['first_name']);
        $this->lastName = trim($data['last_name']);
        $this->email = trim($data['email']);
        $this->username = trim($data['username']);
        $this->password = trim($data['password']);
    }
}
