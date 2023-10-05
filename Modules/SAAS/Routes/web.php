<?php

use Illuminate\Support\Facades\Route;
use Modules\SAAS\Http\Controllers\PlanController;
use Modules\SAAS\Http\Controllers\RoleController;
use Modules\SAAS\Http\Controllers\UserController;
use Modules\SAAS\Http\Controllers\LoginController;
use Modules\SAAS\Http\Controllers\TenantController;
use Modules\SAAS\Http\Controllers\ProfileController;
use Modules\SAAS\Http\Controllers\DashboardController;
use Modules\SAAS\Http\Controllers\Guest\PaymentController;
use Modules\SAAS\Http\Controllers\RegistrationController;
use Modules\SAAS\Http\Controllers\Guest\PlanSelectController;
use Modules\SAAS\Http\Controllers\Auth\VerificationController;
use Modules\SAAS\Http\Controllers\Guest\PlanSubscriptionController;

Route::view('welcome', 'saas::guest.welcome-page')->name('welcome-page');

Route::prefix('saas')->group(function () {
    // Guest User Only
    Route::middleware('is_guest')->group(function () {
        Route::get('register', [RegistrationController::class, 'showForm'])->name('register.showForm');
        Route::post('register', [RegistrationController::class, 'register'])->name('register');
        Route::get('login', [LoginController::class, 'showForm'])->name('login.showForm');
        Route::post('login', [LoginController::class, 'login'])->name('login');
    });
    // Authenticated but Not Verified Yet
    Route::middleware('is_auth')->group(function() {
        Route::get('/email/verify', [VerificationController::class,'show'])->name('verification.notice');
    });

    // Authenticated and Verified User
    Route::middleware(['is_verified'])->group(function () {
        Route::delete('logout', [LoginController::class, 'logout'])->name('logout');

        Route::prefix('dashboard')->group(function () {
            Route::controller(DashboardController::class)->group(function () {
                Route::get('/', 'index')->name('dashboard');
            });
            Route::resource('tenants', TenantController::class);
        });

        Route::get('profile/{user}/edit', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('profile/{user}/update', [ProfileController::class, 'update'])->name('profile.update');
        Route::resource('plans', PlanController::class);

        Route::resource('users', UserController::class);
        Route::delete('users/{user}/trash', [UserController::class, 'trash'])->name('users.trash');
        Route::patch('users/{user}/restore', [UserController::class, 'restore'])->name('users.restore');

        Route::resource('roles', RoleController::class);
    });

    // For All Types of Users
    Route::get('plan/all', [PlanSelectController::class, 'index'])->name('plan.all');
    Route::get('plan/{plan:slug}', [PlanSelectController::class, 'show'])->name('plan.detail');
    Route::get('plan/{plan:slug}/subscribe', [PlanSelectController::class, 'subscribe'])->name('plan.subscribe')->middleware('is_auth');
    Route::post('subscriptions/{plan}', [PlanSubscriptionController::class, 'store'])->name('subscriptions.store');
});
