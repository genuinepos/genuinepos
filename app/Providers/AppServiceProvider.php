<?php

namespace App\Providers;

use App\Models\GeneralSetting;
use App\Services\CacheService;
use App\Services\CacheServiceInterface;
use App\Services\GeneralSettingService;
use App\Services\GeneralSettingServiceInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(GeneralSetting::class, function () {
            return new GeneralSetting();
        });
        $this->app->bind(CacheServiceInterface::class, CacheService::class);
        $this->app->bind(GeneralSettingServiceInterface::class, GeneralSettingService::class);
        $this->app->alias(GeneralSetting::class, 'general-settings');
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Tenant codes moved to (App\Listener\TenantBootstrapped::class)->handle();
    }
}
