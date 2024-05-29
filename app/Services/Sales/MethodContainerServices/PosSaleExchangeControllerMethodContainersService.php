<?php

namespace App\Services\Sales\MethodContainerServices;

use App\Enums\BooleanType;
use App\Enums\SaleScreenType;
use App\Enums\DayBookVoucherType;
use App\Services\Sales\SaleService;
use App\Enums\AccountingVoucherType;
use App\Enums\AccountLedgerVoucherType;
use App\Enums\ProductLedgerVoucherType;
use App\Enums\UserActivityLogActionType;
use App\Enums\UserActivityLogSubjectType;
use App\Services\Accounts\AccountService;
use App\Services\Accounts\DayBookService;
use App\Services\Sales\SaleProductService;
use App\Services\Sales\CashRegisterService;
use App\Services\Sales\SaleExchangeProduct;
use App\Services\Sales\SaleExchangeService;
use App\Services\Products\StockChainService;
use App\Services\Products\ProductStockService;
use App\Services\Users\UserActivityLogService;
use App\Services\Accounts\AccountLedgerService;
use App\Services\Products\ProductLedgerService;
use App\Services\Accounts\AccountingVoucherService;
use App\Services\Sales\CashRegisterTransactionService;
use App\Services\Accounts\AccountingVoucherDescriptionService;
use App\Services\Accounts\AccountingVoucherDescriptionReferenceService;
use App\Interfaces\Sales\PosSaleExchangeControllerMethodContainersInterface;

class PosSaleExchangeControllerMethodContainersService implements PosSaleExchangeControllerMethodContainersInterface
{
    public function __construct(
        private SaleExchangeService $saleExchangeService,
        private SaleExchangeProduct $saleExchangeProduct,
        private SaleService $saleService,
        private SaleProductService $saleProductService,
        private CashRegisterService $cashRegisterService,
        private CashRegisterTransactionService $cashRegisterTransactionService,
        private StockChainService $stockChainService,
        private ProductStockService $productStockService,
        private DayBookService $dayBookService,
        private AccountService $accountService,
        private AccountLedgerService $accountLedgerService,
        private ProductLedgerService $productLedgerService,
        private AccountingVoucherService $accountingVoucherService,
        private AccountingVoucherDescriptionService $accountingVoucherDescriptionService,
        private AccountingVoucherDescriptionReferenceService $accountingVoucherDescriptionReferenceService,
        private UserActivityLogService $userActivityLogService,
    ) {
    }

    public function searchInvoiceMethodContainer(object $request): array|object
    {
        $sale = $this->saleService->singleSaleByAnyCondition(with: ['customer'])->where('invoice_id', $request->invoice_id)->first();

        $saleProducts = $this->saleProductService->exchangeableSaleProducts(saleId: $sale->id);

        if ($sale) {

            if ($sale->exchange_status == BooleanType::True->value) {

                return ['pass' => false, 'msg' => __('Exchange Limit is 1 for per invoice.')];
            }

            if ($sale->sale_screen == SaleScreenType::AddSale->value) {

                return ['pass' => false, 'msg' => __('Sale is created by add sale. If you want to make any exchange on this invoice, Please go to the add sale.')];
            }

            return view('sales.pos.ajax_view.exchange_able_invoice', compact('sale', 'saleProducts'));
        } else {

            return ['pass' => false, 'msg' => __('Invoice Not Found')];
        }
    }

    public function prepareExchangeMethodContainer($request): array
    {
        $saleId = $request->sale_id;
        $sale = $this->saleService->singleSale(id: $saleId, with: ['saleProducts' => function ($query) {
            return $query->where('ex_status', BooleanType::False->value)->orderBy('product_id')->get();
        }, 'saleProducts.product', 'saleProducts.variant', 'saleProducts.unit']);

        $prepareExchange = $this->saleExchangeService->prepareExchange(request: $request, sale: $sale);

        if (isset($prepareExchange['pass']) && $prepareExchange['pass'] == false) {

            return ['pass' => false, 'msg' => $prepareExchange['msg']];
        }

        return ['sale' => $sale];
    }

    public function exchangeConfirmMethodContainer(object $request, object $codeGenerator): array
    {
        $restrictions = $this->saleService->restrictions(request: $request, accountService: $this->accountService, checkCustomerChangeRestriction: true, saleId: $request->ex_sale_id);

        if ($restrictions['pass'] == false) {

            return ['pass' => false, 'msg' => $restrictions['msg']];
        }

        $generalSettings = config('generalSettings');
        $receiptVoucherPrefix = $generalSettings['prefix__receipt_voucher_prefix'] ? $generalSettings['prefix__receipt_voucher_prefix'] : 'RV';
        $stockAccountingMethod = $generalSettings['business_or_shop__stock_accounting_method'];

        $sale = $this->saleService->singleSale(id: $request->ex_sale_id, with: ['saleProducts']);

        $updateExchangeableSale = $this->saleExchangeService->updateExchangeableSale(request: $request, sale: $sale);

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

        $this->stockChainService->updateStockChain(sale: $sale, stockAccountingMethod: $stockAccountingMethod);

        $this->userActivityLogService->addLog(action: UserActivityLogActionType::Added->value, subjectType: UserActivityLogSubjectType::ExchangeInvoice->value, dataObj: $sale);

        $customerCopySaleProducts = $this->saleProductService->customerCopySaleProducts(saleId: $sale->id);

        $printPageSize = $request->print_page_size;
        $changeAmount = $request->change_amount > 0 ? $request->change_amount : 0;

        return ['sale' => $sale, 'customerCopySaleProducts' => $customerCopySaleProducts, 'printPageSize' => $printPageSize, 'changeAmount' => $changeAmount];
    }
}
