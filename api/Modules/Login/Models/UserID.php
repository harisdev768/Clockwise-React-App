<?php
namespace App\Modules\Login\Models;

class UserID {
    private ?int $id = null;

    public function __construct(int $id) {
        $this->id = $id;
    }

    public function setUserId(int $id) {
        $this->id = $id;
    }
    public function getUserIdVal(): ?int {
        return $this->id;
    }
}