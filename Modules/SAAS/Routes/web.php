<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Modules\SAAS\Http\Controllers\RegistrationController;
use Modules\SAAS\Http\Controllers\LoginController;

Route::view('/', 'saas::welcome-page')->name('welcome-page');

Route::get('register', [RegistrationController::class, 'showForm'])->name('register.showForm');
Route::post('register', [RegistrationController::class, 'register'])->name('register');
Route::get('login', [LoginController::class, 'showForm'])->name('login.showForm');
Route::post('login', [LoginController::class, 'login'])->name('login');
