<?php
namespace App\Modules\User\Services;


use App\Modules\User\Models\Hydrators\UserHydrator;
use App\Modules\User\Models\Mappers\UserMapper;
use App\Modules\User\Request\AddUserRequest;

class UserService
{
    private $pdo;
    private UserHydrator $hydrator;
    private UserMapper $mapper;

    public function __construct($pdo, UserHydrator $hydrator, UserMapper $mapper)
    {
        $this->pdo = $pdo;
        $this->hydrator = $hydrator;
        $this->mapper = $mapper;
    }

    public function createUser(AddUserRequest $request): void
    {
        // Check for duplicate email
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
        $stmt->execute([$request->email]);
        if ($stmt->fetchColumn() > 0) {
            die(json_encode(["success" => false, "message" => "Email already exists."]));
        }

        // Check for duplicate username
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
        $stmt->execute([$request->username]);
        if ($stmt->fetchColumn() > 0) {
            die(json_encode(["success" => false, "message" => "Username already exists."]));
        }

        // Hydrate user
        $user = $this->hydrator->fromRequest($request);

        // Map user to DB format and insert
        $stmt = $this->pdo->prepare("
            INSERT INTO users (first_name, last_name, email, username, password_hash)
            VALUES (?, ?, ?, ?, ?)
        ");

        $stmt->execute($this->mapper->toDatabase($user));
    }
}
