<?php

namespace App\Http\Controllers\Sales;

use App\Enums\BooleanType;
use Illuminate\Http\Request;
use App\Enums\SaleScreenType;
use App\Enums\DayBookVoucherType;
use App\Utils\UserActivityLogUtil;
use Illuminate\Support\Facades\DB;
use App\Services\Sales\SaleService;
use App\Enums\AccountingVoucherType;
use App\Http\Controllers\Controller;
use App\Services\Sales\SaleExchange;
use App\Services\Sales\PosSaleService;
use App\Enums\AccountLedgerVoucherType;
use App\Enums\ProductLedgerVoucherType;
use App\Services\CodeGenerationService;
use App\Services\Accounts\AccountService;
use App\Services\Accounts\DayBookService;
use App\Services\Sales\SaleProductService;
use App\Services\Sales\CashRegisterService;
use App\Services\Sales\SaleExchangeProduct;
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
        private SaleExchangeProduct $saleExchangeProduct,
        private SaleService $saleService,
        private SaleProductService $saleProductService,
        private CashRegisterService $cashRegisterService,
        private CashRegisterTransactionService $cashRegisterTransactionService,
        private PurchaseProductService $purchaseProductService,
        private BranchSettingService $branchSettingService,
        private ProductStockService $productStockService,
        private DayBookService $dayBookService,
        private AccountService $accountService,
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
        $sale = $this->saleService->singleSaleByAnyCondition(with: ['customer'])->where('invoice_id', $request->invoice_id)->first();

        $saleProducts = DB::table('sale_products')
            ->where('sale_products.sale_id', $sale->id)
            ->leftJoin('products', 'sale_products.product_id', 'products.id')
            ->leftJoin('product_variants', 'sale_products.variant_id', 'product_variants.id')
            ->leftJoin('units', 'sale_products.unit_id', 'units.id')
            ->select(
                'sale_products.product_id',
                'sale_products.variant_id',
                'sale_products.description',
                'sale_products.unit_price_exc_tax',
                'sale_products.unit_price_inc_tax',
                'sale_products.unit_discount_type',
                'sale_products.unit_discount',
                'sale_products.unit_discount_amount',
                'sale_products.tax_ac_id',
                'sale_products.tax_type',
                'sale_products.unit_tax_percent',
                'sale_products.unit_tax_amount',
                'sale_products.unit_id',
                'products.name as product_name',
                'product_variants.variant_name',
                'units.name as unit_name',
                DB::raw('SUM(sale_products.quantity) as quantity'),
                DB::raw('SUM(sale_products.subtotal) as subtotal'),
            )
            ->groupBy('sale_products.product_id')
            ->groupBy('sale_products.variant_id')
            ->groupBy('sale_products.description')
            ->groupBy('sale_products.unit_price_exc_tax')
            ->groupBy('sale_products.unit_price_inc_tax')
            ->groupBy('sale_products.unit_discount_type')
            ->groupBy('sale_products.unit_discount')
            ->groupBy('sale_products.unit_discount_amount')
            ->groupBy('sale_products.tax_ac_id')
            ->groupBy('sale_products.tax_type')
            ->groupBy('sale_products.unit_tax_percent')
            ->groupBy('sale_products.unit_tax_amount')
            ->groupBy('sale_products.unit_id')
            ->groupBy('products.name')
            ->groupBy('product_variants.variant_name')
            ->groupBy('units.name')
            ->orderBy('sale_products.product_id')
            ->get();

        if ($sale) {

            if ($sale->exchange_status == BooleanType::True->value) {

                return response()->json(['errorMsg' => __('Exchange Limit is 1 for per invoice.')]);
            }

            if ($sale->sale_screen == SaleScreenType::AddSale->value) {

                return response()->json(['errorMsg' => __('Sale is created by add sale. If you want to make any exchange on this invoice, Please go to the add sale.')]);
            }

            return view('sales.pos.ajax_view.exchange_able_invoice', compact('sale', 'saleProducts'));
        } else {

            return response()->json(['errorMsg' => __('Invoice Not Found')]);
        }
    }

    public function prepareExchange(Request $request)
    {
        $saleId = $request->sale_id;
        $sale = $this->saleService->singleSale(id: $saleId, with: ['saleProducts' => function($query){
            return $query->where('ex_status', 0)->orderBy('product_id')->get();
        }, 'saleProducts.product', 'saleProducts.variant', 'saleProducts.unit']);

        $hasExchangeProduct = 0;
        foreach ($sale->saleProducts as $index => $saleProduct) {

            $__exQty = $request->ex_quantities[$index] ? $request->ex_quantities[$index] : 0;
            $variantId = $request->variant_ids[$index] == 'noid' ? null : $request->variant_ids[$index];

            if (
                ($__exQty != 0 && $__exQty != '') &&
                $saleProduct->product_id == $request->product_ids[$index] &&
                $saleProduct->variant_id == $variantId
            ) {

                $hasExchangeProduct = 1;

                $saleProduct->ex_quantity = $__exQty;
                $saleProduct->ex_status = 1;
                $saleProduct->unit_discount_type = $request->unit_discount_types[$index];
                $saleProduct->unit_discount = $request->unit_discounts[$index];
                $saleProduct->unit_discount_amounts = $request->unit_discount_amounts[$index];
                $saleProduct->unit_price_inc_tax = $request->unit_prices_inc_tax[$index];
                $saleProduct->subtotal = $request->subtotals[$index];
            }
        }

        if ($hasExchangeProduct == 0) {

            return response()->json(['errorMsg' => __('Exchange can not go to the next step. All Product quantity is 0.')]);
        }

        $sale->net_total_amount = $request->net_total_amount;

        return response()->json(['sale' => $sale]);
    }

    public function exchangeConfirm(Request $request, CodeGenerationService $codeGenerator)
    {
        try {

            DB::beginTransaction();

            $restrictions = $this->saleService->restrictions(request: $request, accountService: $this->accountService, checkCustomerChangeRestriction: true, saleId: $request->ex_sale_id);

            if ($restrictions['pass'] == false) {

                return response()->json(['errorMsg' => $restrictions['msg']]);
            }

            $generalSettings = config('generalSettings');
            $branchSetting = $this->branchSettingService->singleBranchSetting(branchId: auth()->user()->branch_id);
            $receiptVoucherPrefix = isset($branchSetting) && $branchSetting?->receipt_voucher_prefix ? $branchSetting?->receipt_voucher_prefix : $generalSettings['prefix__receipt'];
            $stockAccountingMethod = $generalSettings['business__stock_accounting_method'];

            $sale = $this->saleService->singleSale(id: $request->ex_sale_id, with: ['saleProducts']);

            $updateExchangeableSale = $this->saleExchange->updateExchangeableSale(request: $request, sale: $sale);

            $this->dayBookService->updateDayBook(voucherTypeId: DayBookVoucherType::Sales->value, date: $updateExchangeableSale->date, accountId: $updateExchangeableSale->customer_account_id, transId: $updateExchangeableSale->id, amount: $updateExchangeableSale->total_invoice_amount, amountType: 'debit');

            // Add Sale A/c Ledger Entry
            $singleAccountLedgerEntry = $this->accountLedgerService->singleLedgerEntry(voucherType: AccountLedgerVoucherType::Sales->value, transId: $updateExchangeableSale->id, accountId: $updateExchangeableSale->sale_account_id);

            $salesLedgerAmount = $singleAccountLedgerEntry->credit + $request->sales_ledger_amount;
            $this->accountLedgerService->updateAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::Sales->value, date: $updateExchangeableSale->date, account_id: $updateExchangeableSale->sale_account_id, trans_id: $updateExchangeableSale->id, amount: $salesLedgerAmount, amount_type: 'credit');

            // Add Customer A/c ledger Entry For Sale Exchange
            $this->accountLedgerService->updateAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::Sales->value, account_id: $updateExchangeableSale->customer_account_id, date: $updateExchangeableSale->date, trans_id: $updateExchangeableSale->id, amount: $updateExchangeableSale->total_invoice_amount, amount_type: 'debit', current_account_id: $updateExchangeableSale->customer_account_id);

            if ($request->sale_tax_ac_id) {

                // Add Tax A/c ledger Entry For Sales
                $this->accountLedgerService->updateAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::Sales->value, account_id: $updateExchangeableSale->sale_tax_ac_id, date: $updateExchangeableSale->date, trans_id: $updateExchangeableSale->id, amount: $updateExchangeableSale->order_tax_amount, amount_type: 'credit');
            }

            foreach ($request->product_ids as $index => $productId) {

                $addSaleExchangeProduct = $this->saleExchangeProduct->addSaleExchangeProduct(request: $request, sale: $updateExchangeableSale, index: $index);

                // Add Product Ledger Entry
                $quantity = $addSaleExchangeProduct->quantity;
                $absQuantity = abs($addSaleExchangeProduct->quantity);
                $this->productLedgerService->addProductLedgerEntry(voucherTypeId: ProductLedgerVoucherType::Exchange->value, date: $updateExchangeableSale->date, productId: $productId, transId: $addSaleExchangeProduct->id, rate: $addSaleExchangeProduct->unit_price_inc_tax, quantityType: ($quantity >= 0 ? 'out' : 'in'), quantity: $absQuantity, subtotal: $addSaleExchangeProduct->subtotal, variantId: $addSaleExchangeProduct->variant_id);

                if ($addSaleExchangeProduct->tax_ac_id) {

                    // Add Tax A/c ledger Entry
                    $ledgerTaxAmount = $addSaleExchangeProduct->unit_tax_amount * $addSaleExchangeProduct->quantity;
                    $absLedgerTaxAmount = abs($ledgerTaxAmount);
                    $this->accountLedgerService->addAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::Exchange->value, date: $updateExchangeableSale->date, account_id: $addSaleExchangeProduct->tax_ac_id, trans_id: $addSaleExchangeProduct->id, amount: $absLedgerTaxAmount, amount_type: ($ledgerTaxAmount >= 0 ? 'credit' : 'debit'));
                }
            }

            $voucherDebitDescriptionId = null;
            if ($request->received_amount > 0) {

                $changeAmount = $request->change_amount > 0 ? $request->change_amount : 0;
                $receivedAmount = $request->received_amount - $changeAmount;

                $addAccountingVoucher = $this->accountingVoucherService->addAccountingVoucher(date: date('Y-m-d'), voucherType: AccountingVoucherType::Receipt->value, remarks: $request->payment_note, codeGenerator: $codeGenerator, voucherPrefix: $receiptVoucherPrefix, debitTotal: $receivedAmount, creditTotal: $receivedAmount, totalAmount: $receivedAmount, saleRefId: $updateExchangeableSale->id);

                // Add Debit Account Accounting voucher Description
                $addAccountingVoucherDebitDescription = $this->accountingVoucherDescriptionService->addAccountingVoucherDescription(accountingVoucherId: $addAccountingVoucher->id, accountId: $request->account_id, paymentMethodId: $request->payment_method_id, amountType: 'dr', amount: $receivedAmount);

                $voucherDebitDescriptionId = $addAccountingVoucherDebitDescription->id;

                //Add Debit Ledger Entry
                $this->accountLedgerService->addAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::Receipt->value, date: date('Y-m-d'), account_id: $request->account_id, trans_id: $addAccountingVoucherDebitDescription->id, amount: $receivedAmount, amount_type: 'debit');

                // Add Payment Description Credit Entry
                $addAccountingVoucherCreditDescription = $this->accountingVoucherDescriptionService->addAccountingVoucherDescription(accountingVoucherId: $addAccountingVoucher->id, accountId: $updateExchangeableSale->customer_account_id, paymentMethodId: null, amountType: 'cr', amount: $receivedAmount, note: $request->payment_note);

                // Add Accounting VoucherDescription References
                $this->accountingVoucherDescriptionReferenceService->addAccountingVoucherDescriptionReferences(accountingVoucherDescriptionId: $addAccountingVoucherCreditDescription->id, accountId: $updateExchangeableSale->customer_account_id, amount: $receivedAmount, refIdColName: 'sale_id', refIds: [$updateExchangeableSale->id]);

                //Add Credit Ledger Entry
                $this->accountLedgerService->addAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::Receipt->value, date: date('Y-m-d'), account_id: $request->customer_account_id, trans_id: $addAccountingVoucherCreditDescription->id, amount: $receivedAmount, amount_type: 'credit', cash_bank_account_id: $request->account_id);
            }

            $sale = $this->saleService->singleSale(
                id: $updateExchangeableSale->id,
                with: [
                    'branch',
                    'branch.parentBranch',
                    'branch.branchSetting:id,add_sale_invoice_layout_id',
                    'branch.branchSetting.addSaleInvoiceLayout',
                    'customer',
                    'saleProducts',
                    'saleProducts.product',
                ]
            );

            if ($sale->due > 0) {

                $this->accountingVoucherDescriptionReferenceService->invoiceOrVoucherDueAmountAutoDistribution(accountId: $sale->customer_account_id, accountingVoucherType: AccountingVoucherType::Receipt->value, refIdColName: 'sale_id', sale: $sale);
            }

            $saleProducts = $sale->saleProducts;
            foreach ($saleProducts as $saleProduct) {

                $this->productStockService->adjustMainProductAndVariantStock($saleProduct->product_id, $saleProduct->variant_id);

                $this->productStockService->adjustBranchAllStock(productId: $saleProduct->product_id, variantId: $saleProduct->variant_id, branchId: $saleProduct->branch_id);

                $this->productStockService->adjustBranchStock(productId: $saleProduct->product_id, variantId: $saleProduct->variant_id, branchId: $saleProduct->branch_id);
            }

            $this->purchaseProductService->updatePurchaseSaleProductChain($sale, $stockAccountingMethod);


            $this->userActivityLogUtil->addLog(action: 1, subject_type: 34, data_obj: $sale);

            $customerCopySaleProducts = $this->saleProductService->customerCopySaleProducts(saleId: $sale->id);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        $changeAmount = $request->change_amount > 0 ? $request->change_amount : 0;
        return view('sales.save_and_print_template.sale_print', compact('sale', 'changeAmount', 'customerCopySaleProducts'));
    }
}
