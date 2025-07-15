<?php
namespace App\Modules\ForgotPassword\Request;

use App\Core\Http\Request;

class ResetPasswordRequest
{
    private array $data;
    private string $token;
    private string $newPassword;



    public function __construct($token, $newPassword)
    {
        $this->token = trim($token);
        $this->newPassword = trim($newPassword);
    }

    //Get all request data
    public function all(): array
    {
        return $this->data;
    }

    public function getToken(): string{
        return $this->token;
    }
    public function getNewPassword(): string{
        return $this->newPassword;
    }
}
