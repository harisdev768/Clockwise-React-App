<?php
namespace App\Modules\ForgotPassword\Response;

class ForgotPasswordResponse
{
    public static function json($data = [], int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    public static function success(string $message, int $statusCode = 200): void
    {
        self::json([
            'success' => true,
            'message' => $message
        ], $statusCode);
    }

    public static function error(string $message = 'An error occurred', int $statusCode = 400): void
    {
        self::json([
            'success' => false,
            'message' => $message,
        ], $statusCode);
    }

    public static function unauthorized(string $message = 'Unauthorized'): void
    {
        self::json([
            'success' => false,
            'message' => $message,
        ], 401);
    }

    public static function notFound(string $message = 'Not found'): void
    {
        self::json([
            'success' => false,
            'message' => $message,
        ], 404);



    }

    public static function invalidEmail(string $message = 'If your email is registered, you will receive a reset link.'): void
    {
        self::json([
            'success' => true,
            'message' => $message,
        ], 404);

    }

}