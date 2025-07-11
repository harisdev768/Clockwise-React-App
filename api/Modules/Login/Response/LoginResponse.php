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

    public static function success(array $userData, string $message = 'Login successful'): void
    {
//        print_r($userData);
        self::json([
            'success' => true,
            'message' => $message,
            'user_id' => $userData['user_id'] ?? null,
            'token' => $userData['token'] ?? null
        ], 200);
    }
}