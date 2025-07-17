<?php
namespace App\Modules\Login\Requests;

class CookieRequest{
    private $cookie;

    public function __construct(){
        $this->cookie = $_COOKIE['jwt'] ?? null;
    }

    public function getCookie(){
        return $this->cookie;
    }
}