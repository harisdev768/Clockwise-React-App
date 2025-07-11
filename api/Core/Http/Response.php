<?php
namespace App\Core\Http;

class Response
{
    public static function json($data = [], int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    public static function success($data = [], string $message = 'Success', int $statusCode = 200): void
    {
        self::json([
            'success' => true,
            'message' => $message,
            'data' => $data,
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

    public static function logout(string $message = 'Logged out'): void
    {
        self::json([
            'success' => true,
            'message' => $message,
        ], 200);
    }

}