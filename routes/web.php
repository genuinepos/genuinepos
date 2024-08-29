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
])->group(function () {
    // Guest User
    Auth::routes(['register' => false, 'verify' => true]);
    Route::view('pin_login', 'auth.pin_login');
    // Impersonate User
    Route::get('impersonate/{token}', [UserImpersonateController::class, 'impersonate'])->name('users.impersonate');
    // Authenticated User
    Route::middleware([])->group(function () {
        // Route::middleware(['plan_check'])->group(function() {
        Route::middleware(['auth', 'startup', 'subscriptionRestrictions', 'changeLocation'])->group(base_path('routes/dev_routes.php'));
        Route::middleware(['auth', 'startup', 'subscriptionRestrictions', 'changeLocation'])->group(base_path('routes/admin.php'));
        Route::middleware(['auth', 'startup', 'subscriptionRestrictions', 'changeLocation'])->group(base_path('routes/branches.php'));
        Route::middleware(['auth', 'startup', 'subscriptionRestrictions', 'changeLocation'])->group(base_path('routes/hrms.php'));
        Route::middleware(['auth', 'startup', 'subscriptionRestrictions', 'changeLocation'])->group(base_path('routes/task_management.php'));
        Route::middleware(['auth', 'startup', 'subscriptionRestrictions', 'changeLocation'])->group(base_path('routes/manufacturing.php'));
        Route::middleware(['auth', 'startup', 'subscriptionRestrictions', 'changeLocation'])->group(base_path('routes/contacts.php'));
        Route::middleware(['auth', 'startup', 'subscriptionRestrictions', 'changeLocation'])->group(base_path('routes/accounts.php'));
        Route::middleware(['auth', 'startup', 'subscriptionRestrictions', 'changeLocation'])->group(base_path('routes/sales.php'));
        Route::middleware(['auth', 'startup', 'subscriptionRestrictions', 'changeLocation'])->group(base_path('routes/products.php'));
        Route::middleware(['auth', 'startup', 'subscriptionRestrictions', 'changeLocation'])->group(base_path('routes/setups.php'));
        Route::middleware(['auth', 'startup', 'subscriptionRestrictions', 'changeLocation'])->group(base_path('routes/purchases.php'));
        Route::middleware(['auth', 'startup', 'subscriptionRestrictions', 'changeLocation'])->group(base_path('routes/general_searches.php'));
        Route::middleware(['auth', 'startup', 'subscriptionRestrictions', 'changeLocation'])->group(base_path('routes/stock_adjustments.php'));
        Route::middleware(['auth', 'startup', 'subscriptionRestrictions', 'changeLocation'])->group(base_path('routes/transfer_stocks.php'));
        Route::middleware(['auth', 'startup', 'subscriptionRestrictions', 'changeLocation'])->group(base_path('routes/dashboard.php'));
        Route::middleware(['auth', 'startup', 'subscriptionRestrictions', 'changeLocation'])->group(base_path('routes/today_summary.php'));
        Route::middleware(['auth', 'startup', 'subscriptionRestrictions', 'changeLocation'])->group(base_path('routes/users.php'));
        Route::middleware(['auth', 'startup', 'subscriptionRestrictions', 'changeLocation'])->group(base_path('routes/communication.php'));
        Route::middleware(['auth', 'startup', 'subscriptionRestrictions', 'changeLocation'])->group(base_path('routes/advertisements.php'));
        Route::middleware(['auth', 'startup', 'subscriptionRestrictions', 'changeLocation'])->group(base_path('routes/services.php'));
        Route::middleware(['auth', 'startup', 'subscriptionRestrictions', 'changeLocation'])->group(base_path('routes/short_menus.php'));
        Route::middleware(['auth', 'startup'])->group(base_path('routes/billing.php'));
        Route::middleware(['auth', 'startup'])->group(base_path('routes/change_location.php'));
        Route::middleware('auth')->group(base_path('routes/startup.php'));
    });
});

/*
|--------------------------------------------------------------------------
| Redirects to landlord or tenant based on request type
|--------------------------------------------------------------------------
 */
Route::get('/', function (Request $request) {
    $isTenant = tenant();
    return isset($isTenant) ?
        redirect(RouteServiceProvider::HOME) :
        redirect()->route('saas.welcome-page');
})->middleware(['universal', InitializeTenancyByDomainOrSubdomain::class]);
