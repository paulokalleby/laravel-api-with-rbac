<?php

namespace App\Services;

use App\Models\User;
use App\Traits\Deviceable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService
{
    use Deviceable;

    public function login(Request $request): array
    {
        $data = $request->validated();

        $user = User::where('email', $data['email'])->first();

        if (! $user || ! Hash::check($data['password'], $user->password)) {
            throw ValidationException::withMessages([
                'message' => ['As credenciais fornecidas estão incorretas.'],
            ]);
        }

        if (! $user->is_active) {
            throw ValidationException::withMessages([
                'message' => ['A conta do usuário está inativa.'],
            ]);
        }

        // $user->tokens()->delete();

        $device = $this->resolveDevice($request->userAgent());

        $token = $user->createToken($device);

        $token->accessToken->ip_address = $request->ip();
        $token->accessToken->save();

        return [
            'user' => $user,
            'token' => $token->plainTextToken,
        ];
    }

    public function register(array $data): User
    {
        return User::create($data);
    }

    public function logout(): void
    {
        request()->user()?->currentAccessToken()?->delete();
    }

    public function getAuthenticatedUser(): ?User
    {
        return request()->user();
    }

    public function updateProfile(array $data): User
    {
        $user = request()->user();
        $user->update($data);

        return $user;
    }
}
