<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Modules\SAAS\Http\Controllers\Auth\VerificationController;
use Modules\SAAS\Http\Controllers\DashboardController;
use Modules\SAAS\Http\Controllers\DomainAvailabilityController;
use Modules\SAAS\Http\Controllers\Guest\GuestTenantController;
use Modules\SAAS\Http\Controllers\Guest\PlanSelectController;
use Modules\SAAS\Http\Controllers\Guest\PlanSubscriptionController;
use Modules\SAAS\Http\Controllers\LoginController;
use Modules\SAAS\Http\Controllers\PlanController;
use Modules\SAAS\Http\Controllers\ProfileController;
use Modules\SAAS\Http\Controllers\RegistrationController;
use Modules\SAAS\Http\Controllers\RoleController;
use Modules\SAAS\Http\Controllers\TenantController;
use Modules\SAAS\Http\Controllers\UserController;

Route::get('welcome', fn () => Auth::check() ? redirect()->route('saas.dashboard') : redirect()->route('saas.login.showForm'))->name('welcome-page');
// Route::get('welcome', fn() => view('saas::guest.welcome-page'))->name('welcome-page');

// Guest User
Route::middleware('is_guest')->group(function () {
    // Route::get('register', [RegistrationController::class, 'showForm'])->name('register.showForm');
    // Route::post('register', [RegistrationController::class, 'register'])->name('register');
    Route::get('login', [LoginController::class, 'showForm'])->name('login.showForm');
    Route::post('login', [LoginController::class, 'login'])->name('login');
});

// All User
Route::get('plan/all', [PlanSelectController::class, 'index'])->name('plan.all');
Route::get('plan/{plan:slug}', [PlanSelectController::class, 'show'])->name('plan.detail');
Route::post('subscriptions/{plan}', [PlanSubscriptionController::class, 'store'])->name('subscriptions.store');
Route::get('plan/{plan:slug}/subscribe', [PlanSelectController::class, 'subscribe'])->name('plan.subscribe');
Route::get('plan/{plan:slug}/confirm', [PlanSelectController::class, 'confirm'])->name('plan.confirm');
Route::post('guest/tenants/store', [GuestTenantController::class, 'store'])->name('guest.tenants.store');
Route::get('domain/checkAvailability', [DomainAvailabilityController::class, 'checkAvailability'])->name('domain.checkAvailability');

// Auth User **Not-Verified
Route::middleware('is_auth')->group(function () {
    Route::get('/email/verify', [VerificationController::class, 'show'])->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', [VerificationController::class, 'verify'])->middleware(['signed'])->name('verification.verify');
    Route::post('/email/verify/resend', [VerificationController::class, 'resend'])->name('verification.resend');
    Route::delete('logout', [LoginController::class, 'logout'])->name('logout');
});

// Auth and Verified
Route::middleware(['is_verified'])->group(function () {

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
