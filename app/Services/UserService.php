<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class UserService
{
    public function __construct(protected User $user) {}

    public function getAllUsers(array $filters = []): Collection|LengthAwarePaginator
    {
        $paginate = data_get($filters, 'paginate');
        $filters  = collect($filters)->except('paginate')->toArray();

        $query =  $this->user
            ->with(['roles'])
            ->applyFilters($filters)
            ->applySort($filters['sort'] ?? null);

        return is_numeric($paginate)
            ? $query->paginate($paginate)
            : $query->get();
    }

    public function findUserById(string $id): User
    {
        return $this->user->with('roles')->findOrFail($id);
    }

    public function createUser(array $data): User
    {
        $user = $this->user->create($data);

        $user->attachRoles($data['roles'] ?? []);

        return $user->load('roles');
    }

    public function updateUser(array $data, string $id): User
    {
        $user =  $this->user->findOrFail($id);

        $user->update($data);

        $user->syncRoles($data['roles'] ?? []);

        return $user->load('roles');
    }

    public function deleteUser(string $id): bool
    {
        return  $this->user->findOrFail($id)->delete();
    }
}
