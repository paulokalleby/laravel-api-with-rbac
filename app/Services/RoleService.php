<?php

namespace App\Services;

use App\Models\Role;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class RoleService
{
    public function __construct(protected Role $role) {}

    public function getAllRoles(array $filters = []): Collection|LengthAwarePaginator
    {
        $paginate = $filters['paginate'] ?? null;
        unset($filters['paginate']);

        $query =  $this->role
            ->with(['users', 'permissions'])
            ->applyFilters($filters)
            ->applySort($filters['sort'] ?? null);

        return $paginate && is_numeric($paginate)
            ? $query->paginate($paginate)
            : $query->get();
    }

    public function findRoleById(string $id): Role
    {
        return $this->role->with(['users', 'permissions'])->findOrFail($id);
    }

    public function createRole(array $data): Role
    {
        $role = $this->role->create($data);

        $role->attachPermissions($data['permissions'] ?? []);

        return $role->load(['users', 'permissions']);
    }

    public function updateRole(array $data, string $id): Role
    {
        $role = $this->role->findOrFail($id);

        $role->update($data);

        $role->syncPermissions($data['permissions'] ?? []);

        return $role->load(['users', 'permissions']);
    }

    public function deleteRole(string $id): bool
    {
        return $this->role->findOrFail($id)->delete();
    }
}
