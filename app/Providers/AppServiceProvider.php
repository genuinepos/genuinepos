<?php

namespace App\Providers;

use App\Models\GeneralSetting;
use App\Services\CacheService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use App\Services\CacheServiceInterface;
use App\Services\CodeGenerationService;
use App\Services\GeneralSettingService;
use Illuminate\Support\ServiceProvider;
use App\Services\GeneralSettingServiceInterface;
use App\Interfaces\CodeGenerationServiceInterface;
use App\Interfaces\Hrm\PayrollControllerMethodContainersInterface;
use App\Interfaces\Sales\DraftControllerMethodContainersInterface;
use App\Interfaces\Sales\AddSaleControllerMethodContainersInterface;
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
        // $this->app->singleton(GeneralSetting::class, function () {
        //     return new GeneralSetting();
        // });

        $this->app->bind(AddSaleControllerMethodContainersInterface::class, AddSaleControllerMethodContainersService::class);
        $this->app->bind(SalesOrderControllerMethodContainersInterface::class, SalesOrderControllerMethodContainersService::class);
        $this->app->bind(QuotationControllerMethodContainersInterface::class, QuotationControllerMethodContainersService::class);
        $this->app->bind(DraftControllerMethodContainersInterface::class, DraftControllerMethodContainersService::class);
        $this->app->bind(StockAdjustmentControllerMethodContainersInterface::class, StockAdjustmentControllerMethodContainersService::class);
        $this->app->bind(ProductionControllerMethodContainersInterface::class, ProductionControllerMethodContainersService::class);
        $this->app->bind(ReceiptControllerMethodContainersInterface::class, ReceiptControllerMethodContainersService::class);
        $this->app->bind(PaymentControllerMethodContainersInterface::class, PaymentControllerMethodContainersService::class);
        $this->app->bind(ExpenseControllerMethodContainersInterface::class, ExpenseControllerMethodContainersService::class);
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
        try {
            // Cache::forget('generalSettings');
            Cache::rememberForever('generalSettings', function () {

                return GeneralSetting::where('branch_id', auth()->user()?->branch_id ?? null)->pluck('value', 'key')->toArray();
            });

            $generalSettings = Cache::get('generalSettings') ?? GeneralSetting::where('branch_id', auth()->user()?->branch_id ?? null)->pluck('value', 'key')->toArray();

            config([
                'generalSettings' => $generalSettings,
                'mail.mailers.smtp.transport' => $generalSettings['email_config__MAIL_MAILER'] ?? config('mail.mailers.smtp.transport'),
                'mail.mailers.smtp.host' => $generalSettings['email_config__MAIL_HOST'] ?? config('mail.mailers.smtp.host'),
                'mail.mailers.smtp.port' => $generalSettings['email_config__MAIL_PORT'] ?? config('mail.mailers.smtp.port'),
                'mail.mailers.smtp.encryption' => $generalSettings['email_config__MAIL_ENCRYPTION'] ?? config('mail.mailers.smtp.encryption'),
                'mail.mailers.smtp.username' => $generalSettings['email_config__MAIL_USERNAME'] ?? config('mail.mailers.smtp.username'),
                'mail.mailers.smtp.password' => $generalSettings['email_config__MAIL_PASSWORD'] ?? config('mail.mailers.smtp.password'),
                // 'mail.mailers.smtp.timeout' => $generalSettings['email_config__MAIL_TIMEOUT'] ?? config('mail.mailers.smtp.timeout'),
                // 'mail.mailers.smtp.auth_mode' => $generalSettings['email_config__MAIL_AUTH_MODE'] ?? config('mail.mailers.smtp.auth_mode'),
            ]);

            $dateFormat = $generalSettings['business__date_format'];
            $__date_format = str_replace('-', '/', $dateFormat);
            if (isset($generalSettings)) {
                view()->share('generalSettings', $generalSettings);
                view()->share('__date_format', $__date_format);
            }
        } catch (Exception $e) {
        }
    }
}
