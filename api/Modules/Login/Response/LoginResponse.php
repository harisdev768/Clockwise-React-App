<?php
namespace App\Modules\Login\Response;


class LoginResponse {

    public static function json($data = [], int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    public static function success(array $userData, string $message , string $token): void
    {
        self::json([
            'success' => true,
            'message' => $message,
            'user_id' => $userData['user_id'] ?? null,
            'name' => $userData['name'] ?? null,
            'email' => $userData['email'] ?? null,
            'role' => $userData['role'] ?? null,
            'token' => $token ?? null
        ], 200);


    }
    public static function error(string $message): void{
        self::json([
            'success' => false,
            'message' => $message
        ], 200);
    }
}