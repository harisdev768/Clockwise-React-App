<?php

namespace App\Modules\ForgotPassword\Request;

use App\Core\Http\Request;

    class ForgotPasswordRequest{
    private array $data;
    private string $email;

    public function __construct($email)
    {
        $this->email = $email;
    }

    public function getEmail(){
        return $this->email;
    }


}
