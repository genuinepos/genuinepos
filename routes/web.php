<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Providers\RouteServiceProvider;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomainOrSubdomain;

/*
|--------------------------------------------------------------------------
| WebApp Route Files Registration
|--------------------------------------------------------------------------
*/

Route::middleware(['web', InitializeTenancyByDomainOrSubdomain::class, PreventAccessFromCentralDomains::class])->group(function () {
    // Guest User
    Auth::routes();
    Route::view('pin_login', 'auth.pin_login');

    // Authenticated User
    Route::middleware('auth')->group(base_path('routes/dev_routes.php'));
    Route::middleware('auth')->group(base_path('routes/admin.php'));
    Route::middleware('auth')->group(base_path('routes/hrms.php'));
    Route::middleware('auth')->group(base_path('routes/essential.php'));
    Route::middleware('auth')->group(base_path('routes/manufacturing.php'));
    Route::middleware('auth')->group(base_path('routes/contacts.php'));
    Route::middleware('auth')->group(base_path('routes/accounts.php'));
    Route::middleware('auth')->group(base_path('routes/sales.php'));
    Route::middleware('auth')->group(base_path('routes/products.php'));
});

/*
|--------------------------------------------------------------------------
| Common welcome/home URI ("/" route) for main platform and all tenants
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    $isTenant = tenant()?->id;
    if (isset($isTenant)) {
        return redirect(RouteServiceProvider::HOME);
    }
    return view('saas::welcome-page');
// });
})->middleware(['universal', InitializeTenancyByDomainOrSubdomain::class]);
