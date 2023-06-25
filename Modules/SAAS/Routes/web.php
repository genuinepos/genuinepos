<?php

use Illuminate\Support\Facades\Route;
use Modules\SAAS\Http\Controllers\DashboardController;
use Modules\SAAS\Http\Controllers\LoginController;
use Modules\SAAS\Http\Controllers\RegistrationController;
use Modules\SAAS\Http\Controllers\WelcomeController;

Route::get('/', WelcomeController::class)->name('welcome-page');

Route::prefix('saas')->group(function() {
    Route::middleware('is_guest')->group(function() {
        Route::get('register', [RegistrationController::class, 'showForm'])->name('register.showForm');
        Route::post('register', [RegistrationController::class, 'register'])->name('register');
        Route::get('login', [LoginController::class, 'showForm'])->name('login.showForm');
        Route::post('login', [LoginController::class, 'login'])->name('login');
    });
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');
});

Route::prefix('saas/dashboard')->middleware('is_auth')->group(function() {
    Route::controller(DashboardController::class)->group(function() {
        Route::get('/', 'index')->name('dashboard');
    });
});
