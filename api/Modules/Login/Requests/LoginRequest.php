<?php
namespace App\Modules\Login\Requests;

class LoginRequest {
    private array $data;
    private string $email;
    private string $password;
    private string $username;

    public function __construct()
    {
        $contentType = $_SERVER['CONTENT_TYPE'] ?? '';

        if (stripos($contentType, 'application/json') !== false) {
            $raw = file_get_contents("php://input");
            $decoded = json_decode($raw, true);
            $this->data = is_array($decoded) ? $decoded : [];
        } else {
            $this->data = $_POST ?: $_GET;
        }

        // Assign values from the input array
        $this->email = $this->data['email'] ?? '';
        $this->password = $this->data['password'] ?? '';
        $this->username = $this->data['username'] ?? '';
    }

    public function getEmail(): string { //username datatype
        return $this->email;
    }

    public function getPassword(): string {
        return $this->password;
    }

    public function getUsername(): string {
        return $this->username;
    }

    public function all(): array {
        return $this->data;
    }
}
