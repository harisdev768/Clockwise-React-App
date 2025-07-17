<?php
namespace App\Modules\Login\UseCases;

use App\Core\Http\Response;
use App\Modules\Login\Services\JWTService;
use App\Modules\Login\Exceptions\TokenException;

class ValidateToken{
    private JWTService $jwtService;
    public function __construct(JWTService $jwtService){
        $this->jwtService = $jwtService;
    }
    public function verify(string $token){


        $decoded = $this->jwtService->validateToken($token);

        if ($decoded) {
            return Response::success($decoded, 'Authenticated');
        } else {
            throw TokenException::invalidToken();
        }
    }
}