<?php

use Illuminate\Support\Str;
use Modules\SAAS\Entities\Tenant;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use Modules\SAAS\Http\Controllers\LoginController;
use Modules\SAAS\Http\Controllers\TenantController;
use Modules\SAAS\Http\Controllers\WelcomeController;
use Modules\SAAS\Http\Controllers\DashboardController;
use Modules\SAAS\Http\Controllers\RegistrationController;

Route::get('/', WelcomeController::class)->name('welcome-page');

Route::prefix('saas')->group(function () {
    Route::middleware('is_guest')->group(function () {
        Route::get('register', [RegistrationController::class, 'showForm'])->name('register.showForm');
        Route::post('register', [RegistrationController::class, 'register'])->name('register');
        Route::get('login', [LoginController::class, 'showForm'])->name('login.showForm');
        Route::post('login', [LoginController::class, 'login'])->name('login');
    });
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');
});

Route::prefix('saas/dashboard')->middleware('is_auth')->group(function () {
    Route::controller(DashboardController::class)->group(function () {
        Route::get('/', 'index')->name('dashboard');
    });
    Route::resource('tenants', TenantController::class);
});

Route::get('ct', function () {
    $id = Str::uuid()->toString();
    $id = 'user' . rand(1, 1000);
    $t = Tenant::create(['id' => $id]);
    $t->domains()->create(['domain' => $id]);
    // Tenant::where('id', $t->id)->get()->runForEach(function () {
    //     Artisan::call('migrate --seed');
    // });
});
