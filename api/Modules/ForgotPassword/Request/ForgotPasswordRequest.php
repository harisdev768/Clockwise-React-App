<?php

namespace App\Modules\ForgotPassword\Request;



    class ForgotPasswordRequest{
    private array $data;

    private string $email;

    public function __construct()
    {
        $contentType = $_SERVER['CONTENT_TYPE'] ?? '';

        if (stripos($contentType, 'application/json') !== false) {
            $raw = file_get_contents("php://input");
            $decoded = json_decode($raw, true);
            $this->data = is_array($decoded) ? $decoded : [];
        } else {
            // fallback to standard $_POST or $_GET
            $this->data = $_POST ?: $_GET;
        }
        $this->email = $this->data['email'] ?? null;
    }

    //Get all request data
    public function all(): array
    {
        return $this->data;
    }

    public function getEmail(){
        return $this->email;
    }


}
