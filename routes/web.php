<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


Route::get('/', fn() => view('saas.welcome-page'));
Route::get('/saas/register', fn() => view('saas.register'))->name('saas.register');

Auth::routes();
Route::get('pin_login', fn () => view('auth.pin_login'));

if (config('app.debug')) {
    include_once __DIR__ . '/dev_routes.php';
}
