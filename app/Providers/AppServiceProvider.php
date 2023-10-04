<?php

namespace App\Providers;

use App\Models\GeneralSetting;
use App\Services\CacheService;
use App\Services\CacheServiceInterface;
use App\Services\CodeGenerationService;
use App\Services\GeneralSettingService;
use Illuminate\Support\ServiceProvider;
use App\Services\GeneralSettingServiceInterface;
use App\Interfaces\CodeGenerationServiceInterface;
use App\Interfaces\Sales\DraftControllerMethodContainersInterface;
use App\Interfaces\Sales\AddSaleControllerMethodContainersInterface;
use App\Interfaces\Sales\QuotationControllerMethodContainersInterface;
use App\Interfaces\Sales\SalesOrderControllerMethodContainersInterface;
use App\Services\Sales\MethodContainerServices\DraftControllerMethodContainersService;
use App\Services\Sales\MethodContainerServices\AddSaleControllerMethodContainersService;
use App\Services\Sales\MethodContainerServices\QuotationControllerMethodContainersService;
use App\Services\Sales\MethodContainerServices\SalesOrderControllerMethodContainersService;

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

        $this->app->bind(AddSaleControllerMethodContainersInterface::class, AddSaleControllerMethodContainersService::class);
        $this->app->bind(SalesOrderControllerMethodContainersInterface::class, SalesOrderControllerMethodContainersService::class);
        $this->app->bind(QuotationControllerMethodContainersInterface::class, QuotationControllerMethodContainersService::class);
        $this->app->bind(DraftControllerMethodContainersInterface::class, DraftControllerMethodContainersService::class);
        $this->app->bind(CodeGenerationServiceInterface::class, CodeGenerationService::class);
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
