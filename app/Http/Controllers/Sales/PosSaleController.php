<?php

namespace App\Http\Controllers\Sales;

use App\Enums\SaleStatus;
use Illuminate\Http\Request;
use App\Enums\SaleScreenType;
use App\Enums\DayBookVoucherType;
use App\Utils\UserActivityLogUtil;
use Illuminate\Support\Facades\DB;
use App\Services\Sales\SaleService;
use App\Enums\AccountingVoucherType;
use App\Http\Controllers\Controller;
use App\Services\Products\UnitService;
use App\Services\Sales\PosSaleService;
use App\Services\Setups\BranchService;
use App\Enums\AccountLedgerVoucherType;
use App\Enums\ProductLedgerVoucherType;
use App\Services\Products\BrandService;
use App\Services\Accounts\AccountService;
use App\Services\Accounts\DayBookService;
use App\Services\Contacts\ContactService;
use App\Services\Products\CategoryService;
use App\Services\Sales\SaleProductService;
use App\Services\Sales\CashRegisterService;
use App\Services\Products\PriceGroupService;
use App\Services\Contacts\RewardPointService;
use App\Services\Setups\BranchSettingService;
use App\Services\Setups\PaymentMethodService;
use App\Services\Products\ProductStockService;
use App\Services\Accounts\AccountFilterService;
use App\Services\Accounts\AccountLedgerService;
use App\Services\Products\ProductLedgerService;
use App\Interfaces\CodeGenerationServiceInterface;
use App\Services\Products\ManagePriceGroupService;
use App\Services\Purchases\PurchaseProductService;
use App\Services\Accounts\AccountingVoucherService;
use App\Services\Sales\CashRegisterTransactionService;
use App\Services\Accounts\AccountingVoucherDescriptionService;
use App\Services\Accounts\AccountingVoucherDescriptionReferenceService;

class PosSaleController extends Controller
{
    public function __construct(
        private SaleService $saleService,
        private PosSaleService $posSaleService,
        private SaleProductService $saleProductService,
        private CashRegisterService $cashRegisterService,
        private BrandService $brandService,
        private CategoryService $categoryService,
        private PurchaseProductService $purchaseProductService,
        private PriceGroupService $priceGroupService,
        private ManagePriceGroupService $managePriceGroupService,
        private PaymentMethodService $paymentMethodService,
        private AccountService $accountService,
        private AccountFilterService $accountFilterService,
        private BranchService $branchService,
        private BranchSettingService $branchSettingService,
        private ProductStockService $productStockService,
        private DayBookService $dayBookService,
        private AccountLedgerService $accountLedgerService,
        private ProductLedgerService $productLedgerService,
        private AccountingVoucherService $accountingVoucherService,
        private AccountingVoucherDescriptionService $accountingVoucherDescriptionService,
        private AccountingVoucherDescriptionReferenceService $accountingVoucherDescriptionReferenceService,
        private CashRegisterTransactionService $cashRegisterTransactionService,
        private ContactService $contactService,
        private RewardPointService $rewardPointService,
        private UnitService $unitService,
        private UserActivityLogUtil $userActivityLogUtil,
    ) {
    }

    public function create()
    {
        if (!auth()->user()->can('pos_add')) {

            abort(403, 'Access Forbidden.');
        }

        $openedCashRegister = $this->cashRegisterService->singleCashRegister(with: ['user', 'branch', 'branch.parentBranch', 'cashCounter'])
            ->where('user_id', auth()->user()->id)
            ->where('status', 1)
            ->first();

        if ($openedCashRegister) {

            $ownBranchIdOrParentBranchId = auth()->user()?->branch?->parent_branch_id ? auth()->user()?->branch?->parent_branch_id : auth()->user()->branch_id;

            $branchName = $this->branchService->branchName();

            $categories = $this->categoryService->categories()->where('parent_category_id', null)->get(['id', 'name']);

            $brands = $this->brandService->brands()->get(['id', 'name']);

            $units = $this->unitService->units()->get(['id', 'name', 'code_name']);

            $priceGroupProducts = $this->managePriceGroupService->priceGroupProducts();

            $priceGroups = $this->priceGroupService->priceGroups()->get(['id', 'name']);

            $accounts = $this->accountService->accounts(with: [
                'bank:id,name',
                'group:id,sorting_number,sub_sub_group_number',
                'bankAccessBranch',
            ])->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
                ->where('branch_id', auth()->user()->branch_id)
                ->whereIn('account_groups.sub_sub_group_number', [2])
                ->select('accounts.id', 'accounts.name', 'accounts.account_number', 'accounts.bank_id', 'accounts.account_group_id', 'account_groups.sub_sub_group_number')
                ->orWhereIn('account_groups.sub_sub_group_number', [1, 11])->get();

            $accounts = $this->accountFilterService->filterCashBankAccounts($accounts);

            $methods = $this->paymentMethodService->paymentMethods(with: ['paymentMethodSetting'])->get();

            $taxAccounts = $this->accountService->accounts()
                ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
                ->where('account_groups.sub_sub_group_number', 8)
                ->get(['accounts.id', 'accounts.name', 'tax_percent']);

            $customerAccounts = $this->accountService->customerAndSupplierAccounts($ownBranchIdOrParentBranchId);

            return view('sales.pos.create', compact(
                'branchName',
                'openedCashRegister',
                'categories',
                'brands',
                'units',
                'priceGroups',
                'priceGroupProducts',
                'accounts',
                'methods',
                'taxAccounts',
                'customerAccounts',
            ));
        } else {

            return redirect()->route('cash.register.create');
        }
    }

    public function store(Request $request, CodeGenerationServiceInterface $codeGenerator)
    {
        // return $request->all();
        try {

            DB::beginTransaction();

            $generalSettings = config('generalSettings');
            $branchSetting = $this->branchSettingService->singleBranchSetting(branchId: auth()->user()->branch_id);
            $invoicePrefix = isset($branchSetting) && $branchSetting?->sale_invoice_prefix ? $branchSetting?->sale_invoice_prefix : $generalSettings['prefix__sale_invoice'];
            $quotationPrefix = isset($branchSetting) && $branchSetting?->quotation_prefix ? $branchSetting?->quotation_prefix : 'Q';
            $receiptVoucherPrefix = isset($branchSetting) && $branchSetting?->receipt_voucher_prefix ? $branchSetting?->receipt_voucher_prefix : $generalSettings['prefix__receipt'];

            $stockAccountingMethod = $generalSettings['business__stock_accounting_method'];

            $restrictions = $this->saleService->restrictions(request: $request, accountService: $this->accountService);
            if ($restrictions['pass'] == false) {

                return response()->json(['errorMsg' => $restrictions['msg']]);
            }

            $addPosSale = $this->posSaleService->addPosSale(request: $request, saleScreenType: SaleScreenType::PosSale->value, codeGenerator: $codeGenerator, invoicePrefix: $invoicePrefix, quotationPrefix: $quotationPrefix, dateFormat: $generalSettings['business__date_format']);

            if ($request->status == SaleStatus::Final->value) {

                // Add Day Book entry for Final Sale
                $this->dayBookService->addDayBook(voucherTypeId: DayBookVoucherType::Sales->value, date: date('Y-m-d'), accountId: $request->customer_account_id, transId: $addPosSale->id, amount: $request->total_invoice_amount, amountType: 'debit');
            }

            if ($request->status == SaleStatus::Final->value) {

                // Add Sale A/c Ledger Entry
                $this->accountLedgerService->addAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::Sales->value, date: date('Y-m-d'), account_id: $request->sale_account_id, trans_id: $addPosSale->id, amount: $request->sales_ledger_amount, amount_type: 'credit');

                // Add supplier A/c ledger Entry For Purchase
                $this->accountLedgerService->addAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::Sales->value, account_id: $request->customer_account_id, date: date('Y-m-d'), trans_id: $addPosSale->id, amount: $request->total_invoice_amount, amount_type: 'debit');

                if ($request->sale_tax_ac_id) {

                    // Add Tax A/c ledger Entry For Purchase
                    $this->accountLedgerService->addAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::Sales->value, account_id: $request->sale_tax_ac_id, date: date('Y-m-d'), trans_id: $addPosSale->id, amount: $request->order_tax_amount, amount_type: 'credit');
                }
            }

            foreach ($request->product_ids as $index => $productId) {

                $addSaleProduct = $this->saleProductService->addSaleProduct(request: $request, sale: $addPosSale, index: $index);

                if ($request->status == SaleStatus::Final->value) {

                    // Add Product Ledger Entry
                    $this->productLedgerService->addProductLedgerEntry(voucherTypeId: ProductLedgerVoucherType::Sales->value, date: date('Y-m-d'), productId: $productId, transId: $addSaleProduct->id, rate: $addSaleProduct->unit_price_inc_tax, quantityType: 'out', quantity: $addSaleProduct->quantity, subtotal: $addSaleProduct->subtotal, variantId: $addSaleProduct->variant_id);

                    if ($addSaleProduct->tax_ac_id) {

                        // Add Tax A/c ledger Entry
                        $this->accountLedgerService->addAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::SaleProductTax->value, date: date('Y-m-d'), account_id: $addSaleProduct->tax_ac_id, trans_id: $addSaleProduct->id, amount: ($addSaleProduct->unit_tax_amount * $addSaleProduct->quantity), amount_type: 'credit');
                    }
                }
            }

            $voucherDebitDescriptionId = null;
            if ($request->status == SaleStatus::Final->value && $request->received_amount > 0) {

                $changeAmount = $request->change_amount > 0 ? $request->change_amount : 0;
                $receivedAmount = $request->received_amount - $changeAmount;

                $addAccountingVoucher = $this->accountingVoucherService->addAccountingVoucher(date: date('Y-m-d'), voucherType: AccountingVoucherType::Receipt->value, remarks: $request->payment_note, codeGenerator: $codeGenerator, voucherPrefix: $receiptVoucherPrefix, debitTotal: $receivedAmount, creditTotal: $receivedAmount, totalAmount: $receivedAmount, saleRefId: $addPosSale->id);

                // Add Debit Account Accounting voucher Description
                $addAccountingVoucherDebitDescription = $this->accountingVoucherDescriptionService->addAccountingVoucherDescription(accountingVoucherId: $addAccountingVoucher->id, accountId: $request->account_id, paymentMethodId: $request->payment_method_id, amountType: 'dr', amount: $receivedAmount);

                $voucherDebitDescriptionId = $addAccountingVoucherDebitDescription->id;

                //Add Debit Ledger Entry
                $this->accountLedgerService->addAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::Receipt->value, date: date('Y-m-d'), account_id: $request->account_id, trans_id: $addAccountingVoucherDebitDescription->id, amount: $receivedAmount, amount_type: 'debit');

                // Add Payment Description Credit Entry
                $addAccountingVoucherCreditDescription = $this->accountingVoucherDescriptionService->addAccountingVoucherDescription(accountingVoucherId: $addAccountingVoucher->id, accountId: $request->customer_account_id, paymentMethodId: null, amountType: 'cr', amount: $receivedAmount, note: $request->payment_note);

                // Add Accounting VoucherDescription References
                $this->accountingVoucherDescriptionReferenceService->addAccountingVoucherDescriptionReferences(accountingVoucherDescriptionId: $addAccountingVoucherCreditDescription->id, accountId: $request->customer_account_id, amount: $receivedAmount, refIdColName: 'sale_id', refIds: [$addPosSale->id]);

                //Add Credit Ledger Entry
                $this->accountLedgerService->addAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::Receipt->value, date: date('Y-m-d'), account_id: $request->customer_account_id, trans_id: $addAccountingVoucherCreditDescription->id, amount: $receivedAmount, amount_type: 'credit', cash_bank_account_id: $request->account_id);
            }

            $sale = $this->saleService->singleSale(
                id: $addPosSale->id,
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

            if ($sale->due > 0 && $sale->status == SaleStatus::Final->value) {

                $this->accountingVoucherDescriptionReferenceService->invoiceOrVoucherDueAmountAutoDistribution(accountId: $request->customer_account_id, accountingVoucherType: AccountingVoucherType::Receipt->value, refIdColName: 'sale_id', sale: $sale);
            }

            if ($request->status == SaleStatus::Final->value) {

                foreach ($request->product_ids as $__index => $productId) {

                    $variantId = $request->variant_ids[$__index] != 'noid' ? $request->variant_ids[$__index] : null;
                    $this->productStockService->adjustMainProductAndVariantStock(productId: $productId, variantId: $variantId);

                    $this->productStockService->adjustBranchAllStock(productId: $productId, variantId: $variantId, branchId: auth()->user()->branch_id);

                    $this->productStockService->adjustBranchStock(productId: $productId, variantId: $variantId, branchId: auth()->user()->branch_id);

                    $this->purchaseProductService->addPurchaseSaleProductChain(sale: $sale, stockAccountingMethod: $stockAccountingMethod);
                }

                $this->cashRegisterTransactionService->addCashRegisterTransaction(request: $request, sale: $sale, voucherDebitDescriptionId: $voucherDebitDescriptionId);
            }

            $customerCurrentRewardPoint = $this->rewardPointService->calculateCustomerPoint(generalSettings: $generalSettings, totalAmount: $request->total_invoice_amount);

            $this->contactService->updateRewardPoint(contactId: $sale?->customer?->contact_id, currentPoint: $customerCurrentRewardPoint);

            $subjectType = '';
            if ($request->status == SaleStatus::Final->value) {
                $subjectType = 7;
            } elseif ($request->status == SaleStatus::Quotation->value) {
                $subjectType = 30;
            } elseif ($request->status == SaleStatus::Draft->value) {
                $subjectType = 29;
            } elseif ($request->status == SaleStatus::Hold->value) {
                $subjectType = 32;
            } elseif ($request->status == SaleStatus::Suspended->value) {
                $subjectType = 33;
            }

            $this->userActivityLogUtil->addLog(action: 1, subject_type: $subjectType, data_obj: $sale);

            $customerCopySaleProducts = $this->saleProductService->customerCopySaleProducts(saleId: $sale->id);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        if ($request->status == SaleStatus::Final->value) {

            $changeAmount = $request->change_amount > 0 ? $request->change_amount : 0;
            $receivedAmount = $request->received_amount;
            return view('sales.save_and_print_template.sale_print', compact('sale', 'receivedAmount', 'changeAmount', 'customerCopySaleProducts'));
        } elseif ($request->status == SaleStatus::Draft->value) {

            $draft = $sale;
            return view('sales.save_and_print_template.draft_print', compact('draft', 'customerCopySaleProducts'));
        } elseif ($request->status == SaleStatus::Quotation->value) {

            $quotation = $sale;
            return view('sales.save_and_print_template.quotation_print', compact('quotation', 'customerCopySaleProducts'));
        }elseif ($request->status == SaleStatus::Hold->value) {

            return response()->json(['holdInvoiceMsg' => __('Invoice is hold.')]);
        }elseif ($request->status == SaleStatus::Suspended->value) {

            return response()->json(['holdInvoiceMsg' => __('Invoice is suspended.')]);
        }
    }
}
