<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SAAS\RegistrationController;
use App\Http\Controllers\SAAS\LoginController;

Route::view('/', 'saas.welcome-page')->name('saas.welcome');
Route::prefix('saas')->as('saas.')->group(function() {
    Route::get('register', [RegistrationController::class, 'showForm'])->name('register.showForm');
    Route::post('register', [RegistrationController::class, 'register'])->name('register');
    Route::get('login', [LoginController::class, 'showForm'])->name('login.showForm');
    Route::post('login', [LoginController::class, 'login'])->name('login');
});

