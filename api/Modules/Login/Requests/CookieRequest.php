<?php
namespace App\Modules\Login\Requests;
use App\Core\Http\Request;
class CookieRequest{
    private $cookie;
    private $token;

    private $data;
    public function __construct()
    {
        $request = new Request();
        $this->data = $request->all();
        $this->token = $this->data['token'];
        $this->cookie = $_COOKIE['jwt'] ?? null;
    }

    public function getCookie(){
        return $this->cookie;
    }
    public function getToken(){
        return $this->token;
    }
}