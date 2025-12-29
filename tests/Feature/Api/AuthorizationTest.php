<?php

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

beforeEach(function () {

    $this->permission = Permission::factory()->create([
        'name' => 'List Users',
        'action' => 'users.index',
    ]);

    $this->role = Role::factory()->create([
        'name' => 'Admin',
    ]);

    $this->role->permissions()->attach($this->permission->id);

    $this->userWithPermission = User::factory()->create();

    $this->userWithPermission->roles()->attach($this->role->id);

    $this->userWithoutPermission = User::factory()->create();
});

test('can access protected route with permission', function () {
    Sanctum::actingAs($this->userWithPermission);

    $response = $this->getJson(route('users.index'));

    $response->assertOk();
});

test('cannot access protected route without permission', function () {
    Sanctum::actingAs($this->userWithoutPermission);

    $response = $this->getJson(route('users.index'));

    $response->assertForbidden();
});

test('cannot access protected route without authentication.', function () {
    $response = $this->getJson(route('users.index'));

    $response->assertUnauthorized();
});
