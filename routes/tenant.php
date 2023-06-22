<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\InitializeTenancyBySubdomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

Route::middleware([
    'web',
    InitializeTenancyBySubdomain::class,
    PreventAccessFromCentralDomains::class,
])->group(function () {
    Auth::routes();
    Route::get('pin_login', fn () => view('auth.pin_login'));
    Route::middleware('auth')->group(base_path('routes/admin.php'));
    Route::middleware('auth')->group(base_path('routes/hrms.php'));
    Route::middleware('auth')->group(base_path('routes/essential.php'));
    Route::middleware('auth')->group(base_path('routes/manufacturing.php'));
});
