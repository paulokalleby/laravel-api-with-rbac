<?php

namespace App\Services;

use App\Mail\ResetPasswordCodeMail;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class PasswordResetService
{
    public function sendResetCode(array $data): void
    {
        $user = DB::table('users')
            ->whereEmail($data['email'])
            ->first();

        if (! $user) {
            throw new ModelNotFoundException('Usuário não encontrado.');
        }

        $code = rand(100000, 999999);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $data['email']],
            ['token' => $code, 'created_at' => Carbon::now()]
        );

        Mail::to($data['email'])->queue(new ResetPasswordCodeMail($code));
    }

    public function verifyResetCode(array $data): void
    {
        $reset = DB::table('password_reset_tokens')
            ->where('email', $data['email'])
            ->where('token', $data['code'])
            ->first();

        if (! $reset) {
            abort(400, 'Código inválido.');
        }

        if (Carbon::parse($reset->created_at)->diffInMinutes(Carbon::now()) > 15) {
            abort(400, 'O código expirou.');
        }
    }

    public function resetPassword(array $data): void
    {
        $passwordReset = DB::table('password_reset_tokens')
            ->whereEmail($data['email'])
            ->whereToken($data['code'])
            ->first();

        if (! $passwordReset) {
            abort(400, 'Código inválido ou expirado.');
        }

        $user = DB::table('users')
            ->whereEmail($data['email'])
            ->first();

        if (! $user) {
            throw new ModelNotFoundException('Usuário não encontrado.');
        }

        DB::table('users')
            ->whereEmail($data['email'])
            ->update(['password' => bcrypt($data['password'])]);

        DB::table('password_reset_tokens')
            ->whereEmail($data['email'])
            ->delete();
    }
}
