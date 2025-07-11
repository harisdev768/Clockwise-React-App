<?php
namespace App\Modules\ForgotPassword\Request;


class ResetPasswordRequest
{
    private array $data;
    private string $token;
    private string $newPassword;



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
        $this->token = $this->data['token'] ?? '';
        $this->newPassword = $this->data['new_password'] ?? '';
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
