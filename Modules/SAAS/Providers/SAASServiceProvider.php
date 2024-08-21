<?php

namespace Modules\SAAS\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Config;
use Modules\SAAS\Services\PlanService;
use Modules\SAAS\Services\RoleService;
use Modules\SAAS\Services\UserService;
use Illuminate\Support\ServiceProvider;
use Modules\SAAS\Console\BackupCommand;
use Modules\SAAS\Services\CouponService;
use Modules\SAAS\Services\TenantService;
use Modules\SAAS\Services\CurrencyService;
use Illuminate\Console\Scheduling\Schedule;
use Modules\SAAS\Console\RolePermissionSync;
use Modules\SAAS\Interfaces\PlanServiceInterface;
use Modules\SAAS\Interfaces\RoleServiceInterface;
use Modules\SAAS\Interfaces\UserServiceInterface;
use Modules\SAAS\Services\TenantServiceInterface;
use Modules\SAAS\Services\UserSubscriptionService;
use Modules\SAAS\Http\Middleware\IsGuestMiddleware;
use Modules\SAAS\Interfaces\CouponServiceInterface;
use Modules\SAAS\Services\EmailVerificationService;
use Modules\SAAS\Interfaces\CurrencyServiceInterface;
use Modules\SAAS\Http\Middleware\PlanCheckerMiddleware;
use Modules\SAAS\Http\Middleware\IsAuthenticatedMiddleware;
use Modules\SAAS\Http\Middleware\IsEmailVerifiedMiddleware;
use Modules\SAAS\Interfaces\UserSubscriptionServiceInterface;
use Modules\SAAS\Services\UserSubscriptionTransactionService;
use Modules\SAAS\Interfaces\EmailVerificationServiceInterface;
use Modules\SAAS\Interfaces\UserSubscriptionTransactionServiceInterface;

class SAASServiceProvider extends ServiceProvider
{
    /**
     * @var string
     */
    protected $moduleName = 'SAAS';

    /**
     * @var string
     */
    protected $moduleNameLower = 'saas';

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->loadMigrationsFrom(module_path($this->moduleName, 'Database/Migrations'));
        $this->commands([
            BackupCommand::class,
            RolePermissionSync::class,
        ]);

        $this->app->booted(function () {
            $schedule = $this->app->make(Schedule::class);
            // $schedule->command('inspire')->everyMinute();
        });

        // $this->app['events']->listen(Modules\SAAS\Events\CustomerRegisteredEvent::class, Modules\SAAS\Listener\CustomerRegisteredListener::class);
        Paginator::useBootstrapFive();
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(RouteServiceProvider::class);
        $this->app->register(EventServiceProvider::class);

        app()->make('router')->aliasMiddleware('is_auth', IsAuthenticatedMiddleware::class);
        app()->make('router')->aliasMiddleware('is_guest', IsGuestMiddleware::class);
        app()->make('router')->aliasMiddleware('is_verified', IsEmailVerifiedMiddleware::class);
        app()->make('router')->aliasMiddleware('plan_check', PlanCheckerMiddleware::class);

        $this->app->bind(TenantServiceInterface::class, TenantService::class);
        $this->app->bind(UserServiceInterface::class, UserService::class);
        $this->app->bind(RoleServiceInterface::class, RoleService::class);
        $this->app->bind(UserSubscriptionServiceInterface::class, UserSubscriptionService::class);
        $this->app->bind(UserSubscriptionTransactionServiceInterface::class, UserSubscriptionTransactionService::class);
        $this->app->bind(CurrencyServiceInterface::class, CurrencyService::class);
        $this->app->bind(PlanServiceInterface::class, PlanService::class);
        $this->app->bind(CouponServiceInterface::class, CouponService::class);
        $this->app->bind(EmailVerificationServiceInterface::class, EmailVerificationService::class);
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->publishes([
            module_path($this->moduleName, 'Config/config.php') => config_path($this->moduleNameLower.'.php'),
        ], 'config');
        $this->mergeConfigFrom(
            module_path($this->moduleName, 'Config/config.php'),
            $this->moduleNameLower
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/'.$this->moduleNameLower);

        $sourcePath = module_path($this->moduleName, 'Resources/views');

        $this->publishes([
            $sourcePath => $viewPath,
        ], ['views', $this->moduleNameLower.'-module-views']);

        $this->loadViewsFrom(array_merge($this->getPublishableViewPaths(), [$sourcePath]), $this->moduleNameLower);
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/'.$this->moduleNameLower);

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, $this->moduleNameLower);
            $this->loadJsonTranslationsFrom($langPath);
        } else {
            $this->loadTranslationsFrom(module_path($this->moduleName, 'Resources/lang'), $this->moduleNameLower);
            $this->loadJsonTranslationsFrom(module_path($this->moduleName, 'Resources/lang'));
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }

    private function getPublishableViewPaths(): array
    {
        $paths = [];
        foreach (Config::get('view.paths') as $path) {
            if (is_dir($path.'/modules/'.$this->moduleNameLower)) {
                $paths[] = $path.'/modules/'.$this->moduleNameLower;
            }
        }

        return $paths;
    }
}
