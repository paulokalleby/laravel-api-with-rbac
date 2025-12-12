<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RoleRequest;
use App\Http\Resources\RoleResource;
use App\Services\RoleService;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function __construct(protected RoleService $role) {}

    public function index(Request $request)
    {
        return RoleResource::collection(
            $this->role->getAllRoles((array) $request->all())
        );
    }

    public function store(RoleRequest $request)
    {
        return new RoleResource(
            $this->role->createRole(
                (array) $request->validated()
            )
        );
    }

    public function show(string $id)
    {
        return new RoleResource(
            $this->role->findRoleById($id)
        );
    }

    public function update(RoleRequest $request, string $id)
    {
        return new RoleResource(
            $this->role->updateRole(
                (array) $request->validated(),
                $id
            )
        );
    }

    public function destroy(string $id)
    {
        $this->role->deleteRole($id);

        return response()->noContent();
    }
}
