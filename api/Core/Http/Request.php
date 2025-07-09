<?php

namespace App\Core\Http;

class Request {
    private array $data;

    public function __construct() {
        $contentType = $_SERVER['CONTENT_TYPE'] ?? '';

        if (stripos($contentType, 'application/json') !== false) {
            $raw = file_get_contents("php://input");
            $decoded = json_decode($raw, true);
            $this->data = is_array($decoded) ? $decoded : [];
        } else {
            // fallback to standard $_POST or $_GET
            $this->data = $_POST ?: $_GET;
        }
    }

    /**
     * Get all request data
     *
     * @return array
     */
    public function all(): array {
        return $this->data;
    }

    /**
     * Get specific key
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get(string $key, mixed $default = null): mixed {
        return $this->data[$key] ?? $default;
    }

    /**
     * Get entire body (alias for all)
     */
    public function getBody(): array {
        return $this->data;
    }
}
