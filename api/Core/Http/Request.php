<?php
// api/Core/Http/Request.php
namespace App\Core\Http;

class Request {
    public array $get;
    public array $post;
    public array $server;

    public function __construct() {
        $this->get = $_GET;
        $this->post = $_POST;
        $this->server = $_SERVER;
    }

    public function input(string $key, $default = null) {
        return $this->post[$key] ?? $this->get[$key] ?? $default;
    }

    public function all(): array {
        return array_merge($this->get, $this->post);
    }
}
