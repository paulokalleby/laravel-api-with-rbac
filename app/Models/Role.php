<?php

namespace App\Models;

use App\Traits\Filterable;
use App\Traits\Relationable;
use App\Traits\Sortable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use Filterable, HasFactory, HasUuids, Relationable, SoftDeletes, Sortable;

    protected $fillable = [
        'name',
        'is_active',
    ];

    protected array $filterable = [
        'name'      => 'like',
        'is_active' => '=',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'role_user');
    }

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'permission_role');
    }
}
