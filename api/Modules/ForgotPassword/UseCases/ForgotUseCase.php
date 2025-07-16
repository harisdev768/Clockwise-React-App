<?php
namespace App\Modules\ForgotPassword\UseCases;

use App\Modules\ForgotPassword\Request\ForgotPasswordRequest;
use App\Modules\ForgotPassword\Services\ForgotPasswordService;
use App\Modules\Login\Models\User;

class ForgotUseCase
{
    private ForgotPasswordService $forgotPasswordService;

    public function __construct(ForgotPasswordService $forgotPasswordService)
    {
        $this->forgotPasswordService = $forgotPasswordService;
    }

    public function execute(ForgotPasswordRequest $request)
    {

        $user = new User();
        $user->setEmail($request->getEmail());

        $this->forgotPasswordService->sendResetEmail($user);
    }
}
