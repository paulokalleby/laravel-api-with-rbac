<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('can register a user', function () {
    $response = $this->postJson(route('auth.register'), [
        'name'     => 'Maria da Silva',
        'email'    => 'maria@example.com',
        'password' => 'password123',
    ]);

    $response->assertCreated()
        ->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'email',
                'is_active',
                'permissions',
            ],
        ]);

    $this->assertDatabaseHas('users', [
        'email' => 'maria@example.com',
    ]);
});

test('can log in with valid credentials', function () {
    User::factory()->create([
        'email'    => 'maria@example.com',
        'password' => 'password123',
    ]);

    $response = $this->postJson(route('auth.login'), [
        'email'    => 'maria@example.com',
        'password' => 'password123',
        'device'   => 'iPhone',
    ]);

    $response->assertOk()
        ->assertJsonStructure([
            'data' => ['id', 'name', 'email', 'is_active', 'permissions'],
            'token',
        ]);
});

test('can access protected route with token', function () {
    User::factory()->create([
        'email'    => 'maria@example.com',
        'password' => 'password123',
    ]);

    $login = $this->postJson(route('auth.login'), [
        'email'    => 'maria@example.com',
        'password' => 'password123',
        'device'   => 'iPhone',
    ]);

    $token = $login->json('token');

    $response = $this->getJson(route('auth.me'), [
        'Authorization' => "Bearer $token",
    ]);

    $response->assertOk()
        ->assertJsonPath('data.email', 'maria@example.com');
});

test('can update profile', function () {
    User::factory()->create([
        'email'    => 'maria@example.com',
        'password' => 'password123',
    ]);

    $token = $this->postJson(route('auth.login'), [
        'email'    => 'maria@example.com',
        'password' => 'password123',
        'device'   => 'iPhone',
    ])->json('token');

    $response = $this->putJson(route('auth.profile'), [
        'name' => 'Maria Atualizada',
    ], [
        'Authorization' => "Bearer $token",
    ]);

    $response->assertOk()->assertJsonPath('data.name', 'Maria Atualizada');
});

test('can log out', function () {
    User::factory()->create([
        'email'    => 'maria@example.com',
        'password' => 'password123',
    ]);

    $token = $this->postJson(route('auth.login'), [
        'email'    => 'maria@example.com',
        'password' => 'password123',
        'device'   => 'iPhone',
    ])->json('token');

    $response = $this->postJson(route('auth.logout'), [], [
        'Authorization' => "Bearer $token",
    ]);

    $response->assertOk()->assertJsonFragment([
        'message' => 'Logout realizado com sucesso!',
    ]);
});
