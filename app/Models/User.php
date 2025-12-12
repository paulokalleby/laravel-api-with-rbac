<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Traits\Filterable;
use App\Traits\Relationable;
use App\Traits\Sortable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, HasUuids, SoftDeletes, Notifiable, Sortable, Filterable, Relationable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
        'is_active',
    ];

    protected array $filterable = [
        'name'      => 'like',
        'email'     => 'like',
        'is_admin'  => '=',
        'is_active' => '=',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'  => 'hashed',
            'is_admin'  => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    protected function password(): Attribute
    {
        return Attribute::make(
            set: fn(?string $value) => !empty($value) ? bcrypt($value) : $this->password,
        );
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_user');
    }

    public function permissions()
    {
        return $this->roles->map->permissions
            ->flatten()
            ->pluck('action')
            ->unique()
            ->values();
    }
}
