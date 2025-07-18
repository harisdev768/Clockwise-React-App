<?php
namespace App\Modules\User\Controllers;

use App\Modules\User\UseCases\AddUserUseCase;
use App\Modules\User\Request\AddUserRequest;

class AddUserController
{
    private AddUserUseCase $useCase;

    public function __construct(AddUserUseCase $useCase)
    {
        $this->useCase = $useCase;
    }

    public function handle(): void
    {
        header('Content-Type: application/json');

        $input = json_decode(file_get_contents('php://input'), true);
        $request = new AddUserRequest($input);

        $this->useCase->execute($request);

        echo json_encode([
            "success" => true,
            "message" => "User added successfully."
        ]);
    }
}
