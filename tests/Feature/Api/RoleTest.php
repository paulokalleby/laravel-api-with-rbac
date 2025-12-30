<?php

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

beforeEach(function () {

    $this->admin = User::factory()->create([
        'is_admin' => true,
        'name'     => 'admin@example.com',
    ]);

    Sanctum::actingAs($this->admin);
});

test('can list roles', function () {

    Role::factory()->count(3)->create();

    $response = $this->getJson(route('roles.index'));

    $response->assertOk()->assertJsonCount(3, 'data');
});

test('can create a role', function () {

    $response = $this->postJson(route('roles.store'), [
        'name' => 'Novo Papel',
    ]);

    $response->assertCreated()
        ->assertJsonPath('data.name', 'Novo Papel')
        ->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'is_active',
                'created_at',
                'updated_at',
                'users',
                'permissions',
            ],
        ]);

    $this->assertDatabaseHas('roles', [
        'name' => 'Novo Papel',
    ]);
});

test('can view a specific role', function () {

    $role = Role::factory()->create();

    $response = $this->getJson(route('roles.show', $role->id));

    $response->assertOk()
        ->assertJsonPath('data.id', $role->id)
        ->assertJsonPath('data.name', $role->name)
        ->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'is_active',
                'created_at',
                'updated_at',
                'users',
                'permissions',
            ],
        ]);
});

test('can update a role', function () {
    $role = Role::factory()->create([
        'name' => 'Nome Antigo',
    ]);

    $response = $this->putJson(route('roles.update', $role->id), [
        'name' => 'Nome Atualizado',
    ]);

    $response->assertOk()
        ->assertJsonPath('data.name', 'Nome Atualizado')
        ->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'is_active',
                'created_at',
                'updated_at',
                'users',
                'permissions',
            ],
        ]);

    $this->assertDatabaseHas('roles', [
        'id'   => $role->id,
        'name' => 'Nome Atualizado',
    ]);
});

test('can delete a role', function () {

    $role = Role::factory()->create();

    $response = $this->deleteJson(route('roles.destroy', $role->id));

    $response->assertNoContent();

    $this->assertSoftDeleted('roles', [
        'id' => $role->id,
    ]);
});

test('cannot create role with invalid data', function () {

    $response = $this->postJson(route('roles.store'), [
        'name' => 'n',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['name']);
});

test('cannot view non-existing role', function () {

    $response = $this->getJson(route('roles.show', '999'));

    $response->assertNotFound();
});

test('cannot update non-existing role', function () {

    $response = $this->putJson(route('roles.update', '999'), [
        'name' => 'Teste',
    ]);

    $response->assertNotFound();
});

test('cannot delete non-existing role', function () {

    $response = $this->deleteJson(route('roles.destroy', '999'));

    $response->assertNotFound();
});
