<?php

use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Modules\SAAS\Http\Controllers\UserImpersonateController;
use Stancl\Tenancy\Middleware\CheckTenantForMaintenanceMode;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomainOrSubdomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

/*
|--------------------------------------------------------------------------
| WebApp Route Files Registration
|--------------------------------------------------------------------------
*/

Route::middleware([
    'web',
    // 'plan_subscription',
    InitializeTenancyByDomainOrSubdomain::class,
    PreventAccessFromCentralDomains::class,
    CheckTenantForMaintenanceMode::class,
    'plan_check',
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
    Route::middleware('auth')->group(base_path('routes/stock_adjustments.php'));
    Route::middleware('auth')->group(base_path('routes/transfer_stocks.php'));
    Route::get('impersonate/{token}', [UserImpersonateController::class, 'impersonate'])->name('users.impersonate');
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
