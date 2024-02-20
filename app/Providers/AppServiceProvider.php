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
use App\Interfaces\Hrm\PayrollControllerMethodContainersInterface;
use App\Interfaces\Sales\DraftControllerMethodContainersInterface;
use App\Interfaces\Sales\AddSaleControllerMethodContainersInterface;
use App\Interfaces\Accounts\ContraControllerMethodContainersInterface;
use App\Interfaces\Sales\QuotationControllerMethodContainersInterface;
use App\Interfaces\Accounts\ExpenseControllerMethodContainersInterface;
use App\Interfaces\Accounts\PaymentControllerMethodContainersInterface;
use App\Interfaces\Accounts\ReceiptControllerMethodContainersInterface;
use App\Interfaces\Sales\SalesOrderControllerMethodContainersInterface;
use App\Interfaces\Hrm\PayrollPaymentControllerMethodContainersInterface;
use App\Interfaces\Manufacturing\ProductionControllerMethodContainersInterface;
use App\Services\Hrm\MethodContainerServices\PayrollControllerMethodContainersService;
use App\Services\Sales\MethodContainerServices\DraftControllerMethodContainersService;
use App\Interfaces\StockAdjustments\StockAdjustmentControllerMethodContainersInterface;
use App\Services\Sales\MethodContainerServices\AddSaleControllerMethodContainersService;
use App\Services\Accounts\MethodContainerServices\ContraControllerMethodContainersService;
use App\Services\Sales\MethodContainerServices\QuotationControllerMethodContainersService;
use App\Services\Accounts\MethodContainerServices\ExpenseControllerMethodContainersService;
use App\Services\Accounts\MethodContainerServices\PaymentControllerMethodContainersService;
use App\Services\Accounts\MethodContainerServices\ReceiptControllerMethodContainersService;
use App\Services\Sales\MethodContainerServices\SalesOrderControllerMethodContainersService;
use App\Services\Hrm\MethodContainerServices\PayrollPaymentControllerMethodContainersService;
use App\Services\Manufacturing\MethodContainerServices\ProductionControllerMethodContainersService;
use App\Services\StockAdjustments\MethodContainerServices\StockAdjustmentControllerMethodContainersService;

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
        $this->app->bind(StockAdjustmentControllerMethodContainersInterface::class, StockAdjustmentControllerMethodContainersService::class);
        $this->app->bind(ProductionControllerMethodContainersInterface::class, ProductionControllerMethodContainersService::class);
        $this->app->bind(ReceiptControllerMethodContainersInterface::class, ReceiptControllerMethodContainersService::class);
        $this->app->bind(PaymentControllerMethodContainersInterface::class, PaymentControllerMethodContainersService::class);
        $this->app->bind(ExpenseControllerMethodContainersInterface::class, ExpenseControllerMethodContainersService::class);
        $this->app->bind(ContraControllerMethodContainersInterface::class, ContraControllerMethodContainersService::class);
        $this->app->bind(PayrollControllerMethodContainersInterface::class, PayrollControllerMethodContainersService::class);
        $this->app->bind(PayrollPaymentControllerMethodContainersInterface::class, PayrollPaymentControllerMethodContainersService::class);
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
