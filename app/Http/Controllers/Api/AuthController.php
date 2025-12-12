<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\ProfileRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\AuthResource;
use App\Services\AuthService;
use App\Services\UserService;

class AuthController extends Controller
{
    public function __construct(
        protected AuthService $auth,
        protected UserService $user
    ) {}

    public function login(LoginRequest $request)
    {
        $auth = $this->auth->login($request->validated());

        return (new AuthResource($auth['user']))->additional([
            'token' => $auth['token']
        ]);
    }

    public function register(RegisterRequest $request)
    {
        return new AuthResource(
            $this->user->createUser(
                $request->validated()
            )
        );
    }

    public function logout()
    {
        $this->auth->logout();

        return response()->json([
            'message' => 'Logout realizado com sucesso!'
        ]);
    }

    public function me()
    {
        return new AuthResource(
            $this->auth->getAuthenticatedUser()
        );
    }

    public function profile(ProfileRequest $request)
    {
        return new AuthResource(
            $this->auth->updateProfile(
                $request->validated()
            )
        );
    }
}
