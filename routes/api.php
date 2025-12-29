<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PasswordController;
use App\Http\Controllers\Api\PermissionController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\UserController;
use App\Http\Middleware\CheckLoggedUserPermissions;
use App\Http\Middleware\LoginThrottle;
use Illuminate\Support\Facades\Route;

/**
 * Auth Routes
 * */
Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login'])->name('auth.login')->middleware(LoginThrottle::class);
    Route::post('/register', [AuthController::class, 'register'])->name('auth.register');
    Route::middleware(['auth:sanctum'])->group(function () {
        Route::get('/me', [AuthController::class, 'me'])->name('auth.me');
        Route::put('/profile', [AuthController::class, 'profile'])->name('auth.profile');
        Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');
    });
});

/**
 * Password Routes
 * */
Route::prefix('password')->group(function () {
    Route::post('/code', [PasswordController::class, 'sendResetCode'])->name('password.code');
    Route::post('/verify', [PasswordController::class, 'verifyResetCode'])->name('password.verify');
    Route::post('/reset', [PasswordController::class, 'passwordReset'])->name('password.reset');
});

/**
 * Dashboard Routers
 * */
Route::middleware(['auth:sanctum', CheckLoggedUserPermissions::class])->group(function () {
    Route::apiResource('/permissions', PermissionController::class)->only('index');
    Route::apiResource('/roles', RoleController::class);
    Route::apiResource('/users', UserController::class);
});
