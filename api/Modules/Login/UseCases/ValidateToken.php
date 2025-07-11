<?php
namespace App\Modules\Login\UseCases;

use App\Core\Http\Response;
use App\Modules\Login\Services\JWTService;

class ValidateToken{
    private JWTService $jwtService;
    public function __construct(JWTService $jwtService){
        $this->jwtService = $jwtService;
    }
    public function verify($token){

        if (!$token) {
            return Response::unauthorized('No token provided');
        }

        $decoded = $this->jwtService->validateToken($token);

        if ($decoded) {
            return Response::success($decoded, 'Authenticated');
        } else {
            return Response::unauthorized('Invalid token');
        }
    }
}