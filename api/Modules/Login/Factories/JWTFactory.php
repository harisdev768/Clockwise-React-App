<?php

namespace App\Modules\Login\Factories;

use App\Config\Container;
use App\Modules\Login\Services\JWTService;


class JWTFactory
{
    private static Container $container;
    private static array $decoded = [];

    public function __construct(Container $container) {
        $this->$container = $container;
    }


    public static function handle(string $token){
        $token = $_COOKIE['jwt'] ?? null;
        if (!$token)
        {
            echo json_encode(['success' => false, 'message' => 'Not authenticated']);
            exit;
        }

        $jwtService = $container->get(JWTService::class);
        $decoded = $jwtService->validateToken($token);

        if ($decoded) {
            echo json_encode(['success' => true, 'user' => $decoded]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid token']);
        }
        exit;
        }

    }