<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;

class AuthService
{
    public function login(array $data): array
    {
        $user = User::where('email', $data['email'])->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            throw ValidationException::withMessages([
                'message' => ['As credenciais fornecidas estão incorretas.'],
            ]);
        }

        if (!$user->is_active) {
            throw ValidationException::withMessages([
                'message' => ['A conta do usuário está inativa.'],
            ]);
        }

        $user->tokens()->delete();

        $token = $user->createToken($data['device'])->plainTextToken;

        return [
            'user'  => $user,
            'token' => $token,
        ];
    }

    public function register(array $data): User
    {
        return User::create($data);
    }

    public function logout(): void
    {
        Auth::user()?->tokens()->delete();
    }

    public function getAuthenticatedUser(): ?User
    {
        return Auth::user();
    }

    public function updateProfile(array $data): User
    {
        $user = Auth::user();
        $user->update($data);

        return $user;
    }
}
