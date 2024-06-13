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
use App\Interfaces\Users\UserControllerMethodContainersInterface;
use App\Interfaces\Hrm\PayrollControllerMethodContainersInterface;
use App\Interfaces\Sales\DraftControllerMethodContainersInterface;
use App\Interfaces\Sales\AddSaleControllerMethodContainersInterface;
use App\Interfaces\Sales\PosSaleControllerMethodContainersInterface;
use App\Interfaces\Setups\BranchControllerMethodContainersInterface;
use App\Interfaces\Sales\DiscountControllerMethodContainersInterface;
use App\Interfaces\Startup\StartupControllerMethodContainerInterface;
use App\Interfaces\Accounts\ContraControllerMethodContainersInterface;
use App\Interfaces\Sales\QuotationControllerMethodContainersInterface;
use App\Interfaces\Accounts\AccountControllerMethodContainersInterface;
use App\Interfaces\Accounts\ExpenseControllerMethodContainersInterface;
use App\Interfaces\Accounts\PaymentControllerMethodContainersInterface;
use App\Interfaces\Accounts\ReceiptControllerMethodContainersInterface;
use App\Interfaces\Contacts\ContactControllerMethodContainersInterface;
use App\Interfaces\Products\ProductControllerMethodContainersInterface;
use App\Interfaces\Sales\SalesOrderControllerMethodContainersInterface;
use App\Interfaces\Sales\SalesReturnControllerMethodContainersInterface;
use App\Interfaces\Hrm\PayrollPaymentControllerMethodContainersInterface;
use App\Interfaces\Purchases\PurchaseControllerMethodContainersInterface;
use App\Interfaces\Products\StockIssueControllerMethodContainersInterface;
use App\Interfaces\Manufacturing\ProcessControllerMethodContainersInterface;
use App\Interfaces\Sales\PosSaleExchangeControllerMethodContainersInterface;
use App\Interfaces\Purchases\PurchaseOrderControllerMethodContainersInterface;
use App\Interfaces\Manufacturing\ProductionControllerMethodContainersInterface;
use App\Interfaces\Products\QuickProductAddControllerMethodContainersInterface;
use App\Interfaces\Purchases\PurchaseReturnControllerMethodContainersInterface;
use App\Interfaces\Sales\SalesOrderToInvoiceControllerMethodContainersInterface;
use App\Interfaces\TransferStocks\TransferStockControllerMethodContainersInterface;
use App\Services\Users\MethodContainerServices\UserControllerMethodContainersService;
use App\Services\Hrm\MethodContainerServices\PayrollControllerMethodContainersService;
use App\Services\Sales\MethodContainerServices\DraftControllerMethodContainersService;
use App\Interfaces\Purchases\PurchaseOrderToInvoiceControllerMethodContainersInterface;
use App\Interfaces\StockAdjustments\StockAdjustmentControllerMethodContainersInterface;
use App\Services\Sales\MethodContainerServices\AddSaleControllerMethodContainersService;
use App\Services\Sales\MethodContainerServices\PosSaleControllerMethodContainersService;
use App\Services\Setups\MethodContainerServices\BranchControllerMethodContainersService;
use App\Services\Sales\MethodContainerServices\DiscountControllerMethodContainersService;
use App\Services\Startup\MethodContainerServices\StartupControllerMethodContainerService;
use App\Services\Accounts\MethodContainerServices\ContraControllerMethodContainersService;
use App\Services\Sales\MethodContainerServices\QuotationControllerMethodContainersService;
use App\Services\Accounts\MethodContainerServices\AccountControllerMethodContainersService;
use App\Services\Accounts\MethodContainerServices\ExpenseControllerMethodContainersService;
use App\Services\Accounts\MethodContainerServices\PaymentControllerMethodContainersService;
use App\Services\Accounts\MethodContainerServices\ReceiptControllerMethodContainersService;
use App\Services\Contacts\MethodContainerServices\ContactControllerMethodContainersService;
use App\Services\Products\MethodContainerServices\ProductControllerMethodContainersService;
use App\Services\Sales\MethodContainerServices\SalesOrderControllerMethodContainersService;
use App\Interfaces\TransferStocks\ReceiveStockFromBranchControllerMethodContainersInterface;
use App\Services\Sales\MethodContainerServices\SalesReturnControllerMethodContainersService;
use App\Services\Hrm\MethodContainerServices\PayrollPaymentControllerMethodContainersService;
use App\Services\Purchases\MethodContainerServices\PurchaseControllerMethodContainersService;
use App\Services\Products\MethodContainerServices\StockIssueControllerMethodContainersService;
use App\Interfaces\TransferStocks\ReceiveStockFromWarehouseControllerMethodContainersInterface;
use App\Services\Manufacturing\MethodContainerServices\ProcessControllerMethodContainersService;
use App\Services\Sales\MethodContainerServices\PosSaleExchangeControllerMethodContainersService;
use App\Services\Purchases\MethodContainerServices\PurchaseOrderControllerMethodContainersService;
use App\Services\Manufacturing\MethodContainerServices\ProductionControllerMethodContainersService;
use App\Services\Products\MethodContainerServices\QuickProductAddControllerMethodContainersService;
use App\Services\Purchases\MethodContainerServices\PurchaseReturnControllerMethodContainersService;
use App\Services\Sales\MethodContainerServices\SalesOrderToInvoiceControllerMethodContainersService;
use App\Services\TransferStocks\MethodContainerServices\TransferStockControllerMethodContainersService;
use App\Services\Purchases\MethodContainerServices\PurchaseOrderToInvoiceControllerMethodContainersService;
use App\Services\StockAdjustments\MethodContainerServices\StockAdjustmentControllerMethodContainersService;
use App\Services\TransferStocks\MethodContainerServices\ReceiveStockFromBranchControllerMethodContainersService;
use App\Services\TransferStocks\MethodContainerServices\ReceiveStockFromWarehouseControllerMethodContainersService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        dd('OK');
        $this->app->singleton(GeneralSetting::class, function () {
            return new GeneralSetting();
        });

        $this->app->bind(ProductControllerMethodContainersInterface::class, ProductControllerMethodContainersService::class);
        $this->app->bind(QuickProductAddControllerMethodContainersInterface::class, QuickProductAddControllerMethodContainersService::class);
        $this->app->bind(ContactControllerMethodContainersInterface::class, ContactControllerMethodContainersService::class);
        $this->app->bind(AddSaleControllerMethodContainersInterface::class, AddSaleControllerMethodContainersService::class);
        $this->app->bind(PosSaleControllerMethodContainersInterface::class, PosSaleControllerMethodContainersService::class);
        $this->app->bind(SalesOrderControllerMethodContainersInterface::class, SalesOrderControllerMethodContainersService::class);
        $this->app->bind(QuotationControllerMethodContainersInterface::class, QuotationControllerMethodContainersService::class);
        $this->app->bind(DraftControllerMethodContainersInterface::class, DraftControllerMethodContainersService::class);
        $this->app->bind(SalesOrderToInvoiceControllerMethodContainersInterface::class, SalesOrderToInvoiceControllerMethodContainersService::class);
        $this->app->bind(PosSaleExchangeControllerMethodContainersInterface::class, PosSaleExchangeControllerMethodContainersService::class);
        $this->app->bind(SalesReturnControllerMethodContainersInterface::class, SalesReturnControllerMethodContainersService::class);
        $this->app->bind(DiscountControllerMethodContainersInterface::class, DiscountControllerMethodContainersService::class);
        $this->app->bind(StockAdjustmentControllerMethodContainersInterface::class, StockAdjustmentControllerMethodContainersService::class);
        $this->app->bind(ProcessControllerMethodContainersInterface::class, ProcessControllerMethodContainersService::class);
        $this->app->bind(ProductionControllerMethodContainersInterface::class, ProductionControllerMethodContainersService::class);
        $this->app->bind(AccountControllerMethodContainersInterface::class, AccountControllerMethodContainersService::class);
        $this->app->bind(ReceiptControllerMethodContainersInterface::class, ReceiptControllerMethodContainersService::class);
        $this->app->bind(PaymentControllerMethodContainersInterface::class, PaymentControllerMethodContainersService::class);
        $this->app->bind(ExpenseControllerMethodContainersInterface::class, ExpenseControllerMethodContainersService::class);
        $this->app->bind(ContraControllerMethodContainersInterface::class, ContraControllerMethodContainersService::class);
        $this->app->bind(PayrollControllerMethodContainersInterface::class, PayrollControllerMethodContainersService::class);
        $this->app->bind(PayrollPaymentControllerMethodContainersInterface::class, PayrollPaymentControllerMethodContainersService::class);
        $this->app->bind(StockIssueControllerMethodContainersInterface::class, StockIssueControllerMethodContainersService::class);
        $this->app->bind(PurchaseControllerMethodContainersInterface::class, PurchaseControllerMethodContainersService::class);
        $this->app->bind(PurchaseOrderControllerMethodContainersInterface::class, PurchaseOrderControllerMethodContainersService::class);
        $this->app->bind(PurchaseOrderToInvoiceControllerMethodContainersInterface::class, PurchaseOrderToInvoiceControllerMethodContainersService::class);
        $this->app->bind(PurchaseReturnControllerMethodContainersInterface::class, PurchaseReturnControllerMethodContainersService::class);
        $this->app->bind(TransferStockControllerMethodContainersInterface::class, TransferStockControllerMethodContainersService::class);
        $this->app->bind(ReceiveStockFromBranchControllerMethodContainersInterface::class, ReceiveStockFromBranchControllerMethodContainersService::class);
        $this->app->bind(ReceiveStockFromWarehouseControllerMethodContainersInterface::class, ReceiveStockFromWarehouseControllerMethodContainersService::class);
        $this->app->bind(UserControllerMethodContainersInterface::class, UserControllerMethodContainersService::class);
        $this->app->bind(CodeGenerationServiceInterface::class, CodeGenerationService::class);
        $this->app->bind(CacheServiceInterface::class, CacheService::class);
        $this->app->bind(GeneralSettingServiceInterface::class, GeneralSettingService::class);
        $this->app->bind(BranchControllerMethodContainersInterface::class, BranchControllerMethodContainersService::class);
        $this->app->bind(StartupControllerMethodContainerInterface::class, StartupControllerMethodContainerService::class);
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
