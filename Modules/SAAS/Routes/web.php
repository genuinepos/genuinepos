<?php

use Illuminate\Support\Facades\Route;
use Modules\SAAS\Http\Controllers\DashboardController;
use Modules\SAAS\Http\Controllers\LoginController;
use Modules\SAAS\Http\Controllers\ProfileController;
use Modules\SAAS\Http\Controllers\RegistrationController;
use Modules\SAAS\Http\Controllers\TenantController;
use Modules\SAAS\Http\Controllers\WelcomeController;

Route::get('/welcome', WelcomeController::class)->name('welcome-page');

Route::prefix('saas')->group(function () {
    // Guest Users
    Route::middleware('is_guest')->group(function () {
        Route::get('register', [RegistrationController::class, 'showForm'])->name('register.showForm');
        Route::post('register', [RegistrationController::class, 'register'])->name('register');
        Route::get('login', [LoginController::class, 'showForm'])->name('login.showForm');
        Route::post('login', [LoginController::class, 'login'])->name('login');
    });

    // Authenticated Users
    Route::middleware('is_auth')->group(function () {
        Route::post('logout', [LoginController::class, 'logout'])->name('logout');
        Route::prefix('dashboard')->group(function () {
            Route::controller(DashboardController::class)->group(function () {
                Route::get('/', 'index')->name('dashboard');
            });
            Route::resource('tenants', TenantController::class);
        });
        Route::get('profile/{user}/edit', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('profile/{user}/update', [ProfileController::class, 'update'])->name('profile.update');
    });
});