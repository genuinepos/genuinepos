<?php

namespace App\Http\Controllers\Sales;

use App\Models\Sales\Sale;
use Illuminate\Http\Request;
use App\Utils\UserActivityLogUtil;
use App\Services\Sales\SaleService;
use App\Http\Controllers\Controller;
use App\Services\Sales\SaleExchange;
use App\Services\Sales\PosSaleService;
use App\Services\Accounts\DayBookService;
use App\Services\Sales\SaleProductService;
use App\Services\Sales\CashRegisterService;
use App\Services\Setups\BranchSettingService;
use App\Services\Products\ProductStockService;
use App\Services\Accounts\AccountLedgerService;
use App\Services\Products\ProductLedgerService;
use App\Services\Purchases\PurchaseProductService;
use App\Services\Accounts\AccountingVoucherService;
use App\Services\Sales\CashRegisterTransactionService;
use App\Services\Accounts\AccountingVoucherDescriptionService;
use App\Services\Accounts\AccountingVoucherDescriptionReferenceService;

class PosSaleExchangeController extends Controller
{
    public function __construct(
        private SaleExchange $saleExchange,
        private SaleService $saleService,
        private PosSaleService $posSaleService,
        private SaleProductService $saleProductService,
        private CashRegisterService $cashRegisterService,
        private CashRegisterTransactionService $cashRegisterTransactionService,
        private PurchaseProductService $purchaseProductService,
        private BranchSettingService $branchSettingService,
        private ProductStockService $productStockService,
        private DayBookService $dayBookService,
        private AccountLedgerService $accountLedgerService,
        private ProductLedgerService $productLedgerService,
        private AccountingVoucherService $accountingVoucherService,
        private AccountingVoucherDescriptionService $accountingVoucherDescriptionService,
        private AccountingVoucherDescriptionReferenceService $accountingVoucherDescriptionReferenceService,
        private UserActivityLogUtil $userActivityLogUtil,
    ) {
    }

    public function searchInvoice(Request $request)
    {
        $sale = $this->saleService->singleSaleByAnyCondition(with: [
            'customer',
            'saleProducts',
            'saleProducts.product',
            'saleProducts.variant',
        ])->where('invoice_id', $request->invoice_id)->first();

        if ($sale) {

            return view('sales.pos.ajax_view.exchange_able_invoice', compact('sale'));
        } else {

            return response()->json(['errorMsg' => 'Invoice Not Fount']);
        }
    }

    public function prepareExchange(Request $request)
    {
        // return $request->all();
        $saleId = $request->sale_id;
        $sale = $this->saleService->singleSale(id: $saleId);

        foreach ($request->ex_quantities as $index => $ex_quantity) {

            $__exQty = $ex_quantity ? $ex_quantity : 0;
            $soldProduct = $this->saleProductService->singleSaleProduct(id: $request->sale_product_ids[$index]);

            if ($__exQty != 0) {

                $soldProduct->ex_quantity = $__exQty;
                $soldProduct->ex_status = 1;
                $soldProduct->save();
            } else {

                $soldProduct->ex_status = 0;
                $soldProduct->save();
            }
        }

        $exchangeableProducts = $this->saleProductService->saleProducts(with: ['product', 'variant', 'sale', 'unit'])
            ->where('sale_id', $sale->id)
            ->where('ex_status', 1)->get();

        $currentStocks = [];
        foreach ($exchangeableProducts as $exchangeableProduct) {

            if ($exchangeableProduct->product->is_manage_stock == 1) {

                $currentStocks[] = PHP_INT_MAX;
            } else {

                $productStock = DB::table('product_stocks')
                    ->where('branch_id', $sale->branch_id)
                    ->where('warehouse_id', null)
                    ->where('product_id', $exchangeableProduct->product_id)
                    ->where('variant_id', $exchangeableProduct->variant_id)
                    ->first();

                $currentStocks[] = $productStock->stock;
            }
        }

        return response()->json([
            'sale' => $sale,
            'exchangeableProducts' => $exchangeableProducts,
            'currentStocks' => $currentStocks,
        ]);
    }

    public function exchangeConfirm(Request $request)
    {
        $restrictions = $this->saleService->restrictions(request: $request, accountService: $accountService, checkCustomerChangeRestriction: true, saleId: $id);

        if ($restrictions['pass'] == false) {

            return response()->json(['errorMsg' => $restrictions['msg']]);
        }

        $generalSettings = config('generalSettings');
        $branchSetting = $this->branchSettingService->singleBranchSetting(branchId: auth()->user()->branch_id);
        $receiptVoucherPrefix = isset($branchSetting) && $branchSetting?->receipt_voucher_prefix ? $branchSetting?->receipt_voucher_prefix : $generalSettings['prefix__receipt'];
        $stockAccountingMethod = $generalSettings['business__stock_accounting_method'];

        $sale = $saleService->singleSale(id: $request->ex_sale_id, with: ['saleProducts']);
    }
}
