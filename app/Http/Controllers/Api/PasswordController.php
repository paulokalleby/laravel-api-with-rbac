<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\PasswordResetRequest;
use App\Http\Requests\Auth\VerifyCodeRequest;
use App\Services\PasswordResetService;

class PasswordController extends Controller
{
    public function __construct(
        protected PasswordResetService $passwordReset
    ) {}

    public function sendResetCode(ForgotPasswordRequest $request)
    {
        $this->passwordReset->sendResetCode(
            $request->validated()
        );

        return response()->json([
            'message' => 'Código enviado para o e-mail!',
        ]);
    }

    public function verifyResetCode(VerifyCodeRequest $request)
    {
        $this->passwordReset->verifyResetCode(
            $request->validated()
        );

        return response()->json([
            'message' => 'Código validado, informe a nova senha!',
        ]);
    }

    public function passwordReset(PasswordResetRequest $request)
    {
        $this->passwordReset->resetPassword(
            $request->validated()
        );

        return response()->json([
            'message' => 'Senha alterada com sucesso!',
        ]);
    }
}
