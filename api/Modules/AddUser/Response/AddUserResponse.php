<?php
namespace App\Modules\AddUser\Response;

class AddUserResponse{

    public static function success(User $user): Response {
        return new Response(201, [
            "success" => true,
            "message" => "User added successfully",
            "data" => $user->toArray()
        ]);
    }

    public static function error(string $message): Response {
        return new Response(400, [
            "success" => false,
            "message" => $message
        ]);
    }

}