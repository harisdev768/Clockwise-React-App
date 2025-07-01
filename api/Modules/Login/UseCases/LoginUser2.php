<?php
//
//namespace App\Modules\Login\UseCases;
//
//use App\Modules\Login\Services\UserService;
//
//class LoginUser2 {
//    private UserService $userService;
//
//    public function __construct(UserService $userService) {
//        $this->userService = $userService;
//    }
//
//    public static function create(UserService $userService): self {
//        return new self($userService);
//    }
//
//    /**
//     * Execute login with given credentials
//     *
//     * @param array $data Must contain 'email' or 'username' and 'password'
//     * @return array
//     */
//    public function execute(array $data): array {
//        $identifier = $data['email'] ?? $data['username'] ?? null;
//        $password = $data['password'] ?? null;
//
//        if (!$identifier || !$password) {
//            if (!$identifier && !$password) {
//                echo "Both email and password can't be empty.\n";
//            }
//            return [
//                'success' => false,
//                'message' => 'Email/username and password are required'
//            ];
//        }
//
//        $user = $this->userService->login($identifier, $password);
//
//        if(!$user){ echo PHP_EOL.'No User - LoginUser.php'.PHP_EOL;}
//
//        if ($user) {
//            $token = bin2hex(random_bytes(16));
//            return [
//                'success' => true,
//                'message' => 'Login successful',
//                'user_id' => $user->getId(),
//                'token' => $token
//            ];
//        }
//
//        return [
//            'success' => false,
//            'message' => 'Invalid credentials   1'
//        ];
//    }
//}
