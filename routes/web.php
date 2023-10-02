<?php

use Illuminate\Http\Request;
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

Route::middleware([
    'web',
    // 'subscription',
    InitializeTenancyByDomainOrSubdomain::class,
    PreventAccessFromCentralDomains::class,
])->group(function () {
    // Guest User
    Auth::routes(['register' => false, 'verify' => true]);
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
    Route::middleware('auth')->group(base_path('routes/setups.php'));
    Route::middleware('auth')->group(base_path('routes/purchases.php'));
    Route::middleware('auth')->group(base_path('routes/general_searches.php'));
});

/*
|--------------------------------------------------------------------------
| Redirects '/' to '/welcome' for landlord, and '/home' for all tenants
|--------------------------------------------------------------------------
*/
Route::get('/', function (Request $request) {
    $isTenant = tenant();
    return isset($isTenant) ?
        redirect(RouteServiceProvider::HOME) :
        redirect()->route('saas.welcome-page');
})->middleware(['universal', InitializeTenancyByDomainOrSubdomain::class]);
