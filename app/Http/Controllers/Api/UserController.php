<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(protected UserService $user) {}

    public function index(Request $request)
    {
        return UserResource::collection(
            $this->user->getAllUsers(
                (array) $request->all()
            )
        );
    }

    public function store(UserStoreRequest $request)
    {
        return new UserResource(
            $this->user->createUser(
                (array) $request->validated()
            )
        );
    }

    public function show(string $id)
    {
        return new UserResource(
            $this->user->findUserById($id)
        );
    }

    public function update(UserUpdateRequest $request, string $id)
    {
        return new UserResource(
            $this->user->updateUser(
                (array) $request->validated(),
                $id
            )
        );
    }

    public function destroy(string $id)
    {
        $this->user->deleteUser($id);

        return response()->noContent();
    }
}
