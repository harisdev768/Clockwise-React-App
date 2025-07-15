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

}