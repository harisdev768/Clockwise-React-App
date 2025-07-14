<?php
namespace App\Modules\Login\Requests;

use App\Core\Http\Request;

class LoginRequest {


    private array $data;
    private string $password;
    private string $email;

    private Request $request;

    public function __construct($email, $password)
    {
        $this->email = $email;
        $this->password = $password;
    }


    public function getEmail(){
        return $this->email;
    }
    public function getEmailString(): string{
        return (string) $this->email;
    }

    public function getPassword() {
        return $this->password;
    }
    public function getPasswordString(): string{
        return (string) $this->password;
    }

    public function serialize(): array {
        return [
            'email' => $this->email,
            'password' => $this->password
        ];
    }

}
