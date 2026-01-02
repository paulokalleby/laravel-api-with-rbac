<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

beforeEach(function () {

    $this->admin = User::factory()->create([
        'name'     => 'admin@example.com',
        'is_admin' => true,
    ]);

    Sanctum::actingAs($this->admin);
});

test('can list users', function () {

    User::factory()->count(3)->create();

    $response = $this->getJson(route('users.index'));

    $response->assertOk()
        ->assertJsonCount(4, 'data');
});

test('can list users with pagination', function () {

    User::factory()->count(15)->create();

    $response = $this->getJson(
        route('users.index', ['paginate' => 10])
    );

    $response->assertOk()
        ->assertJsonStructure([
            'data',
            'links',
            'meta',
        ])
        ->assertJsonCount(10, 'data');
});

test('can create a user', function () {

    $response = $this->postJson(route('users.store'), [
        'name'     => 'Novo Usuário',
        'email'    => 'novo@example.com',
        'password' => 'password123',
    ]);

    $response->assertCreated()
        ->assertJsonPath('data.email', 'novo@example.com')
        ->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'email',
                'is_active',
                'created_at',
                'updated_at',
                'roles',
            ],
        ]);

    $this->assertDatabaseHas('users', [
        'email' => 'novo@example.com',
    ]);
});

test('can view a specific user', function () {

    $user = User::factory()->create();

    $response = $this->getJson(route('users.show', $user->id));

    $response->assertOk()
        ->assertJsonPath('data.id', $user->id)
        ->assertJsonPath('data.name', $user->name)
        ->assertJsonPath('data.email', $user->email)
        ->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'email',
                'is_active',
                'created_at',
                'updated_at',
                'roles',
            ],
        ]);
});

test('can update a user', function () {
    $user = User::factory()->create([
        'name' => 'Nome Antigo',
    ]);

    $response = $this->putJson(route('users.update', $user->id), [
        'name'  => 'Nome Atualizado',
        'email' => $user->email,
    ]);

    $response->assertOk()
        ->assertJsonPath('data.name', 'Nome Atualizado')
        ->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'email',
                'is_active',
                'created_at',
                'updated_at',
                'roles',
            ],
        ]);

    $this->assertDatabaseHas('users', [
        'id'   => $user->id,
        'name' => 'Nome Atualizado',
    ]);
});

test('can delete a user', function () {

    $user = User::factory()->create();

    $response = $this->deleteJson(route('users.destroy', $user->id));

    $response->assertNoContent();

    $this->assertSoftDeleted('users', [
        'id' => $user->id,
    ]);
});

test('cannot create user with invalid data', function () {
    $response = $this->postJson(route('users.index'), [
        'name'     => '',
        'email'    => 'nao-e-email',
        'password' => '',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['name', 'email', 'password']);
});

test('cannot view non-existing user', function () {
    $response = $this->getJson(route('users.show', '999'));

    $response->assertNotFound();
});

test('cannot update non-existing user', function () {
    $response = $this->putJson(route('users.update', '999'), [
        'name'  => 'Teste',
        'email' => 'teste@example.com',
    ]);

    $response->assertNotFound();
});

test('cannot delete non-existing user', function () {
    $response = $this->deleteJson(route('users.destroy', '999'));

    $response->assertNotFound();
});
