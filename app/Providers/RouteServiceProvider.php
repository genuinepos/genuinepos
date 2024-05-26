<?php

namespace App\Providers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * This is used by Laravel authentication to redirect users after login.
     *
     * @var string
     */
    public const HOME = '/';

    /**
     * The controller namespace for the application.
     *
     * When present, controller route declarations will automatically be prefixed with this namespace.
     *
     * @var string|null
     */
    // protected $namespace = 'App\\Http\\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function () {

            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/web.php'));

            Route::middleware(['web', 'auth'])
                ->namespace($this->namespace)
                ->group(base_path('routes/admin.php'));

            Route::middleware(['web', 'auth'])
                ->namespace($this->namespace)
                ->group(base_path('routes/hrms.php'));

            Route::middleware(['web', 'auth'])
                ->namespace($this->namespace)
                ->group(base_path('routes/task_management.php'));

            Route::middleware(['web', 'auth'])
                ->namespace($this->namespace)
                ->group(base_path('routes/manufacturing.php'));

            Route::middleware(['web', 'auth'])
                ->namespace($this->namespace)
                ->group(base_path('routes/contacts.php'));

            Route::middleware(['web', 'auth'])
                ->namespace($this->namespace)
                ->group(base_path('routes/accounts.php'));

            Route::middleware(['web', 'auth'])
                ->namespace($this->namespace)
                ->group(base_path('routes/sales.php'));

            Route::middleware(['web', 'auth'])
                ->namespace($this->namespace)
                ->group(base_path('routes/products.php'));

            Route::middleware(['web', 'auth'])
                ->namespace($this->namespace)
                ->group(base_path('routes/setups.php'));
                
            Route::middleware(['web', 'auth'])
                ->namespace($this->namespace)
                ->group(base_path('routes/purchases.php'));

            Route::middleware(['web', 'auth'])
                ->namespace($this->namespace)
                ->group(base_path('routes/setups.php'));

            Route::middleware(['web', 'auth'])
                ->namespace($this->namespace)
                ->group(base_path('routes/general_searches.php'));

            Route::middleware(['web', 'auth'])
                ->namespace($this->namespace)
                ->group(base_path('routes/stock_adjustments.php'));

            Route::middleware(['web', 'auth'])
                ->namespace($this->namespace)
                ->group(base_path('routes/transfer_stocks.php'));

            Route::middleware(['web', 'auth'])
                ->namespace($this->namespace)
                ->group(base_path('routes/dashboard.php'));

            Route::middleware(['web', 'auth'])
                ->namespace($this->namespace)
                ->group(base_path('routes/short_menus.php'));

            Route::middleware(['web', 'auth'])
                ->namespace($this->namespace)
                ->group(base_path('routes/communication.php'));

            Route::middleware(['web', 'auth'])
                ->namespace($this->namespace)
                ->group(base_path('routes/advertisement.php'));

            Route::middleware(['web', 'auth'])
                ->namespace($this->namespace)
                ->group(base_path('routes/today_summary.php'));

            Route::middleware(['web', 'auth'])
                ->namespace($this->namespace)
                ->group(base_path('routes/users.php'));

            Route::prefix('api')
                ->middleware('api')
                ->namespace($this->namespace)
                ->group(base_path('routes/api.php'));
        });
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by(optional($request->user())->id ?: $request->ip());
        });
    }
}
