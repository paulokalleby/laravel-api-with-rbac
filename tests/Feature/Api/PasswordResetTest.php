<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

uses(RefreshDatabase::class);

test('can reset password with full flow', function () {

    User::factory()->create([
        'email' => 'maria@example.com',
        'password' => 'password123',
    ]);

    $this->postJson(route('password.code'), [
        'email' => 'maria@example.com',
    ])->assertOk();

    $code = DB::table('password_reset_tokens')
        ->where('email', 'maria@example.com')
        ->value('token');

    $this->postJson(route('password.verify'), [
        'email' => 'maria@example.com',
        'code' => $code,
    ])->assertOk();

    $this->postJson(route('password.reset'), [
        'email' => 'maria@example.com',
        'code' => $code,
        'password' => 'novaSenha123',
    ])->assertOk();

    $newLogin = $this->postJson(route('auth.login'), [
        'email' => 'maria@example.com',
        'password' => 'novaSenha123',
        'device' => 'Web',
    ]);

    $newLogin->assertOk()
        ->assertJsonStructure(['data' => ['id', 'email', 'is_active', 'permissions'], 'token']);
});
