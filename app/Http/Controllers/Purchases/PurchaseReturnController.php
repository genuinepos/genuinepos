<?php

namespace App\Http\Controllers\Purchases;

use Carbon\Carbon;


use Illuminate\Http\Request;
use App\Utils\UserActivityLogUtil;
use Illuminate\Support\Facades\DB;
use App\Mail\PurchaseReturnCreated;
use App\Enums\AccountingVoucherType;
use App\Http\Controllers\Controller;
use App\Services\Setups\BranchService;
use App\Services\CodeGenerationService;
use Yajra\DataTables\Facades\DataTables;
use App\Services\Accounts\AccountService;
use App\Services\Accounts\DayBookService;
use App\Services\Setups\WarehouseService;
use App\Services\Purchases\PurchaseService;
use App\Services\Setups\BranchSettingService;
use App\Services\Setups\PaymentMethodService;
use App\Services\Products\ProductStockService;
use App\Services\Accounts\AccountFilterService;
use App\Services\Accounts\AccountLedgerService;
use App\Services\Products\ProductLedgerService;
use App\Services\Purchases\PurchaseReturnService;
use App\Services\Accounts\AccountingVoucherService;
use App\Services\Purchases\PurchaseReturnProductService;
use Modules\Communication\Interface\EmailServiceInterface;
use App\Services\Accounts\AccountingVoucherDescriptionService;
use App\Services\Accounts\AccountingVoucherDescriptionReferenceService;

class PurchaseReturnController extends Controller
{
    public function __construct(
        private PurchaseReturnService $purchaseReturnService,
        private PurchaseReturnProductService $purchaseReturnProductService,
        private PurchaseService $purchaseService,
        private EmailServiceInterface $emailService,
        private UserActivityLogUtil $userActivityLogUtil,
        private PaymentMethodService $paymentMethodService,
        private AccountService $accountService,
        private AccountFilterService $accountFilterService,
        private WarehouseService $warehouseService,
        private BranchService $branchService,
        private BranchSettingService $branchSettingService,
        private ProductStockService $productStockService,
        private DayBookService $dayBookService,
        private AccountLedgerService $accountLedgerService,
        private ProductLedgerService $productLedgerService,
        private AccountingVoucherService $accountingVoucherService,
        private AccountingVoucherDescriptionService $accountingVoucherDescriptionService,
        private AccountingVoucherDescriptionReferenceService $accountingVoucherDescriptionReferenceService,
    ) {
    }

    // Sale return index view
    public function index(Request $request)
    {
        if (!auth()->user()->can('purchase_return')) {

            abort(403, 'Access Forbidden.');
        }

        if ($request->ajax()) {

            return $this->purchaseReturnService->purchaseReturnsTable($request);
        }

        $ownBranchIdOrParentBranchId = auth()?->user()?->branch?->parent_branch_id ? auth()?->user()?->branch?->parent_branch_id : auth()->user()->branch_id;

        $branches = $this->branchService->branches(with: ['parentBranch'])
            ->orderByRaw('COALESCE(branches.parent_branch_id, branches.id), branches.id')->get();

        $supplierAccounts = $this->accountService->customerAndSupplierAccounts($ownBranchIdOrParentBranchId);

        return view('purchase.purchase_return.index', compact('branches', 'supplierAccounts'));
    }

    public function show($id)
    {
        $return = $this->purchaseReturnService->singlePurchaseReturn(id: $id, with: [
            'purchase',
            'branch',
            'branch.parentBranch',
            'supplier:id,name,phone,address',
            'createdBy:id,prefix,name,last_name',
            'purchaseReturnProducts',
            'purchaseReturnProducts.product',
            'purchaseReturnProducts.variant',
            'purchaseReturnProducts.branch:id,name,branch_code,area_name,parent_branch_id',
            'purchaseReturnProducts.branch.parentBranch:id,name,branch_code,area_name',
            'purchaseReturnProducts.warehouse:id,warehouse_name,warehouse_code',
            'purchaseReturnProducts.unit:id,code_name,base_unit_id,base_unit_multiplier',
            'purchaseReturnProducts.unit.baseUnit:id,base_unit_id,code_name',

            'references:id,voucher_description_id,purchase_return_id,amount',
            'references.voucherDescription:id,accounting_voucher_id',
            'references.voucherDescription.accountingVoucher:id,voucher_no,date,voucher_type',
            'references.voucherDescription.accountingVoucher.voucherDescriptions:id,accounting_voucher_id,account_id,payment_method_id',
            'references.voucherDescription.accountingVoucher.voucherDescriptions.paymentMethod:id,name',
            'references.voucherDescription.accountingVoucher.voucherDescriptions.account:id,name,account_number,account_group_id,bank_id,bank_branch',
            'references.voucherDescription.accountingVoucher.voucherDescriptions.account.bank:id,name',
            'references.voucherDescription.accountingVoucher.voucherDescriptions.account.group:id,sub_sub_group_number',
        ]);

        return view('purchase.purchase_return.ajax_view.show', compact('return'));
    }

    // create purchase return view
    public function create()
    {
        if (!auth()->user()->can('purchase_return')) {

            abort(403, 'Access Forbidden.');
        }

        $ownBranchIdOrParentBranchId = auth()->user()?->branch?->parent_branch_id ? auth()->user()?->branch?->parent_branch_id : auth()->user()->branch_id;

        $generalSettings = config('generalSettings');
        $branchName = $this->branchService->branchName();

        $accounts = $this->accountService->accounts(with: [
            'bank:id,name',
            'group:id,sorting_number,sub_sub_group_number',
            'bankAccessBranch'
        ])->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('branch_id', auth()->user()->branch_id)
            ->whereIn('account_groups.sub_sub_group_number', [2])
            ->select('accounts.id', 'accounts.name', 'accounts.account_number', 'accounts.bank_id', 'accounts.account_group_id')
            ->orWhereIn('account_groups.sub_sub_group_number', [1, 11])
            ->get();

        $accounts = $this->accountFilterService->filterCashBankAccounts($accounts);

        $methods = $this->paymentMethodService->paymentMethods(with: ['paymentMethodSetting'])->get();

        $purchaseAccounts = $this->accountService->accounts()
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.sub_group_number', 12)
            ->where('accounts.branch_id', auth()->user()->branch_id)
            ->get(['accounts.id', 'accounts.name']);

        $warehouses = $this->warehouseService->warehouses()->where('branch_id', auth()->user()->branch_id)
            ->orWhere('is_global', 1)->get(['id', 'warehouse_name', 'warehouse_code', 'is_global']);

        $taxAccounts = $this->accountService->accounts()
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.sub_sub_group_number', 8)
            ->where('accounts.branch_id', auth()->user()->branch_id)
            ->get(['accounts.id', 'accounts.name', 'tax_percent']);

        $supplierAccounts = $this->accountService->customerAndSupplierAccounts($ownBranchIdOrParentBranchId);

        return view('purchase.purchase_return.create', compact('accounts', 'methods', 'purchaseAccounts', 'warehouses', 'taxAccounts', 'supplierAccounts', 'branchName'));
    }

    public function store(Request $request, CodeGenerationService $codeGenerator)
    {
        $this->validate($request, [
            'supplier_account_id' => 'required',
            'date' => 'required|date',
            'payment_method_id' => 'required',
            'purchase_account_id' => 'required',
            'account_id' => 'required',
        ], [
            'purchase_account_id.required' => __("Purchase A/c is required."),
            'account_id.required' => __("Credit field must not be is empty."),
            'payment_method_id.required' => __("Payment method field is required."),
            'supplier_account_id.required' => __("Supplier is required."),
        ]);

        try {

            DB::beginTransaction();

            $restrictions = $this->purchaseReturnService->restrictions($request);
            if ($restrictions['pass'] == false) {

                return response()->json(['errorMsg' => $restrictions['msg']]);
            }

            $generalSettings = config('generalSettings');
            $branchSetting = $this->branchSettingService->singleBranchSetting(branchId: auth()->user()->branch_id);
            $purchaseReturnVoucherPrefix = isset($branchSetting) && $branchSetting?->purchase_return_prefix ? $branchSetting?->purchase_return_prefix : $generalSettings['prefix__purchase_return'];
            $receiptVoucherPrefix = isset($branchSetting) && $branchSetting?->receipt_voucher_prefix ? $branchSetting?->receipt_voucher_prefix : $generalSettings['prefix__receipt'];

            $addReturn = $this->purchaseReturnService->addPurchaseReturn(request: $request, voucherPrefix: $purchaseReturnVoucherPrefix, codeGenerator: $codeGenerator);

            $this->dayBookService->addDayBook(voucherTypeId: 6, date: $request->date, accountId: $request->supplier_account_id, transId: $addReturn->id, amount: $request->total_return_amount, amountType: 'debit');

            // Add Purchase A/c Ledger Entry
            $this->accountLedgerService->addAccountLedgerEntry(voucher_type_id: 4, date: $request->date, account_id: $request->purchase_account_id, trans_id: $addReturn->id, amount: $request->purchase_ledger_amount, amount_type: 'credit');

            // Add supplier A/c ledger Entry For Purchase
            $this->accountLedgerService->addAccountLedgerEntry(voucher_type_id: 4, account_id: $request->supplier_account_id, date: $request->date, trans_id: $addReturn->id, amount: $request->total_return_amount, amount_type: 'debit');

            if ($request->return_tax_ac_id) {

                // Add Tax A/c ledger Entry For Purchase
                $this->accountLedgerService->addAccountLedgerEntry(voucher_type_id: 4, account_id: $request->return_tax_ac_id, date: $request->date, trans_id: $addReturn->id, amount: $request->return_tax_amount, amount_type: 'credit');
            }

            $index = 0;
            foreach ($request->product_ids as $productId) {

                $addPurchaseReturnProduct = $this->purchaseReturnProductService->addPurchaseReturnProduct(request: $request, purchaseReturnId: $addReturn->id, index: $index);

                // Add Product Ledger Entry
                $this->productLedgerService->addProductLedgerEntry(voucherTypeId: 4, date: $request->date, productId: $productId, transId: $addPurchaseReturnProduct->id, rate: $addPurchaseReturnProduct->unit_cost_inc_tax, quantityType: 'out', quantity: $addPurchaseReturnProduct->return_qty, subtotal: $addPurchaseReturnProduct->return_subtotal, variantId: $addPurchaseReturnProduct->variant_id, warehouseId: ($addPurchaseReturnProduct->warehouse_id ? $addPurchaseReturnProduct->warehouse_id : null));

                // purchase product tax will be go here
                if ($addPurchaseReturnProduct->tax_ac_id) {

                    // Add Tax A/c ledger Entry
                    $this->accountLedgerService->addAccountLedgerEntry(voucher_type_id: 19, date: $request->date, account_id: $addPurchaseReturnProduct->tax_ac_id, trans_id: $addPurchaseReturnProduct->id, amount: ($addPurchaseReturnProduct->unit_tax_amount * $addPurchaseReturnProduct->return_qty), amount_type: 'credit');
                }

                $index++;
            }

            if ($request->received_amount > 0) {

                $addAccountingVoucher = $this->accountingVoucherService->addAccountingVoucher(date: $request->date, voucherType: AccountingVoucherType::Receipt->value, remarks: $request->receipt_note, codeGenerator: $codeGenerator, voucherPrefix: $receiptVoucherPrefix, debitTotal: $request->received_amount, creditTotal: $request->received_amount, totalAmount: $request->received_amount, purchaseReturnRefId: $addReturn->id);

                // Add Payment Description Credit Entry
                $addAccountingVoucherDebitDescription = $this->accountingVoucherDescriptionService->addAccountingVoucherDescription(accountingVoucherId: $addAccountingVoucher->id, accountId: $request->account_id, paymentMethodId: null, amountType: 'dr', amount: $request->received_amount, note: null);

                //Add debit Ledger Entry
                $this->accountLedgerService->addAccountLedgerEntry(voucher_type_id: 8, date: $request->date, account_id: $request->account_id, trans_id: $addAccountingVoucherDebitDescription->id, amount: $request->received_amount, amount_type: 'debit');

                // Add Credit Account Accounting voucher Description
                $addAccountingVoucherCreditDescription = $this->accountingVoucherDescriptionService->addAccountingVoucherDescription(accountingVoucherId: $addAccountingVoucher->id, accountId: $request->supplier_account_id, paymentMethodId: $request->payment_method_id, amountType: 'cr', amount: $request->received_amount);

                // Add Accounting VoucherDescription References
                $this->accountingVoucherDescriptionReferenceService->addAccountingVoucherDescriptionReferences(accountingVoucherDescriptionId: $addAccountingVoucherCreditDescription->id, accountId: $request->supplier_account_id, amount: $request->received_amount, refIdColName: 'purchase_return_id', refIds: [$addReturn->id]);

                //Add Credit Ledger Entry
                $this->accountLedgerService->addAccountLedgerEntry(voucher_type_id: 8, date: $request->date, account_id: $request->supplier_account_id, trans_id: $addAccountingVoucherCreditDescription->id, amount: $request->received_amount, amount_type: 'credit', cash_bank_account_id: $request->supplier_account_id);
            }

            $__index = 0;
            foreach ($request->product_ids as $productId) {

                $variantId = $request->variant_ids[$__index] != 'noid' ? $request->variant_ids[$__index] : null;
                $this->productStockService->adjustMainProductAndVariantStock(productId: $productId, variantId: $variantId);

                $this->productStockService->adjustBranchAllStock(productId: $productId, variantId: $variantId, branchId: auth()->user()->branch_id);

                if (isset($request->warehouse_ids[$__index])) {

                    $this->productStockService->adjustWarehouseStock(productId: $productId, variantId: $variantId, warehouseId: $request->warehouse_ids[$__index]);
                } else {

                    $this->productStockService->adjustBranchStock($productId, $variantId, branchId: auth()->user()->branch_id);
                }

                $__index++;
            }

            if ($request->purchase_id) {

                $purchase = $this->purchaseService->singlePurchase(id: $request->purchase_id);
                $this->purchaseService->adjustPurchaseInvoiceAmounts($purchase);
            }

            $this->userActivityLogUtil->addLog(action: 1, subject_type: 6, data_obj: $addReturn);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        if ($request->action == 'save_and_print') {

            $receivedAmount = $request->received_amount;
            $return = $this->purchaseReturnService->singlePurchaseReturn(id: $addReturn->id, with: [
                'purchase',
                'branch',
                'branch.parentBranch',
                'supplier',
                'purchaseReturnProducts',
                'purchaseReturnProducts.product',
                'purchaseReturnProducts.variant',
                'purchaseReturnProducts.unit',
            ]);

            return view('purchase.save_and_print_template.print_purchase_return', compact('return', 'receivedAmount'));
        } else {

            return response()->json(['successMsg' => __("Purchase Return Created Successfully.")]);
        }
    }

    public function edit($id)
    {
        $return = $this->purchaseReturnService->singlePurchaseReturn(id: $id, with: [
            'branch',
            'branch.parentBranch',
            'supplier:id,name,phone,address',
            'createdBy:id,prefix,name,last_name',
            'purchaseReturnProducts',
            'purchaseReturnProducts.purchaseProduct',
            'purchaseReturnProducts.product',
            'purchaseReturnProducts.variant',
            'purchaseReturnProducts.branch:id,name,branch_code,area_name,parent_branch_id',
            'purchaseReturnProducts.branch.parentBranch:id,name,branch_code,area_name',
            'purchaseReturnProducts.warehouse:id,warehouse_name,warehouse_code',
            'purchaseReturnProducts.product.unit:id,name,code_name',
            'purchaseReturnProducts.product.unit.childUnits:id,name,code_name,base_unit_id,base_unit_multiplier',
            'purchaseReturnProducts.unit:id,name,code_name,base_unit_multiplier',
        ]);

        $ownBranchIdOrParentBranchId = $return?->branch?->parent_branch_id ? $return?->branch?->parent_branch_id : $return?->branch_id;

        $branchName = $this->branchService->branchName(transObject: $return);

        $accounts = $this->accountService->accounts(with: [
            'bank:id,name',
            'group:id,sorting_number,sub_sub_group_number',
            'bankAccessBranch'
        ])->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('branch_id', $return->branch_id)
            ->whereIn('account_groups.sub_sub_group_number', [2])
            ->select('accounts.id', 'accounts.name', 'accounts.account_number', 'accounts.bank_id', 'accounts.account_group_id')
            ->orWhereIn('account_groups.sub_sub_group_number', [1, 11])
            ->get();

        $accounts = $this->accountFilterService->filterCashBankAccounts($accounts);

        $methods = $this->paymentMethodService->paymentMethods(with: ['paymentMethodSetting'])->get();

        $purchaseAccounts = $this->accountService->accounts()
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.sub_group_number', 12)
            ->where('accounts.branch_id', $return->branch_id)
            ->get(['accounts.id', 'accounts.name']);

        $warehouses = $this->warehouseService->warehouses()->where('branch_id', $return->branch_id)
            ->orWhere('is_global', 1)->get(['id', 'warehouse_name', 'warehouse_code', 'is_global']);

        $taxAccounts = $this->accountService->accounts()
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.sub_sub_group_number', 8)
            // ->where('accounts.branch_id', $return->branch_id)
            ->get(['accounts.id', 'accounts.name', 'tax_percent']);

        $supplierAccounts = $this->accountService->customerAndSupplierAccounts($ownBranchIdOrParentBranchId);

        return view('purchase.purchase_return.edit', compact('return', 'accounts', 'methods', 'purchaseAccounts', 'warehouses', 'taxAccounts', 'supplierAccounts', 'branchName'));
    }

    public function update($id, Request $request, CodeGenerationService $codeGenerator)
    {
        $this->validate($request, [
            'supplier_account_id' => 'required',
            'date' => 'required|date',
            'payment_method_id' => 'required',
            'purchase_account_id' => 'required',
            'account_id' => 'required',
        ], [
            'purchase_account_id.required' => __("Purchase A/c is required."),
            'account_id.required' => __("Credit field must not be is empty."),
            'payment_method_id.required' => __("Payment method field is required."),
            'supplier_account_id.required' => __("Supplier is required."),
        ]);

        $restrictions = $this->purchaseReturnService->restrictions(request: $request, checkSupplierChangeRestriction: true, purchaseReturnId: $id);
        if ($restrictions['pass'] == false) {

            return response()->json(['errorMsg' => $restrictions['msg']]);
        }

        try {

            DB::beginTransaction();

            $generalSettings = config('generalSettings');
            $branchSetting = $this->branchSettingService->singleBranchSetting(branchId: auth()->user()->branch_id);
            $receiptVoucherPrefix = isset($branchSetting) && $branchSetting?->receipt_voucher_prefix ? $branchSetting?->receipt_voucher_prefix : $generalSettings['prefix__receipt'];

            $return = $this->purchaseReturnService->singlePurchaseReturn(id: $id, with: ['purchaseReturnProducts']);

            $storedCurrParentPurchaseId = $return->purchase_id;
            $storedCurrPurchaseAccountId = $return->purchase_account_id;
            $storedCurrSupplierAccountId = $return->supplier_account_id;
            $storedCurrReturnTaxAccountId = $return->return_tax_ac_id;

            $updateReturn = $this->purchaseReturnService->updatePurchaseReturn(request: $request, updatePurchaseReturn: $return);

            // Update Day Book entry for Purchase
            $this->dayBookService->updateDayBook(voucherTypeId: 6, date: $request->date, accountId: $request->supplier_account_id, transId: $updateReturn->id, amount: $request->total_return_amount, amountType: 'debit');

            // Update supplier A/c ledger Entry For Purchase
            $this->accountLedgerService->updateAccountLedgerEntry(voucher_type_id: 4, account_id: $request->supplier_account_id, date: $request->date, trans_id: $updateReturn->id, amount: $request->total_return_amount, amount_type: 'debit', branch_id: $updateReturn->branch_id);

            // Update Purchase A/c Ledger Entry
            $this->accountLedgerService->updateAccountLedgerEntry(voucher_type_id: 4, date: $request->date, account_id: $request->purchase_account_id, trans_id: $updateReturn->id, amount: $request->purchase_ledger_amount, amount_type: 'credit', branch_id: $updateReturn->branch_id);

            if ($request->return_tax_ac_id) {

                // Update Tax A/c ledger Entry For Purchase
                $this->accountLedgerService->updateAccountLedgerEntry(voucher_type_id: 4, account_id: $request->return_tax_ac_id, date: $request->date, trans_id: $updateReturn->id, amount: $request->return_tax_amount, amount_type: 'credit', branch_id: $updateReturn->branch_id);
            } else {

                $this->accountLedgerService->deleteUnusedLedgerEntry(voucherType: 4, transId: $updateReturn->id, accountId: $storedCurrReturnTaxAccountId);
            }

            $index = 0;
            foreach ($request->product_ids as $productId) {

                $updatePurchaseReturnProduct = $this->purchaseReturnProductService->updatePurchaseReturnProduct(request: $request, purchaseReturnId: $updateReturn->id, index: $index);

                // Update Product Ledger Entry
                $this->productLedgerService->updateProductLedgerEntry(voucherTypeId: 4, date: $request->date, productId: $productId, transId: $updatePurchaseReturnProduct->id, rate: $updatePurchaseReturnProduct->unit_cost_inc_tax, quantityType: 'out', quantity: $updatePurchaseReturnProduct->return_qty, subtotal: $updatePurchaseReturnProduct->return_subtotal, variantId: $updatePurchaseReturnProduct->variant_id, warehouseId: ($updatePurchaseReturnProduct->warehouse_id ? $updatePurchaseReturnProduct->warehouse_id : null), currentWarehouseId: $updatePurchaseReturnProduct->current_warehouse_id, branchId: $updateReturn->branch_id);

                if ($updatePurchaseReturnProduct->tax_ac_id) {

                    // Update Tax A/c ledger Entry
                    $this->accountLedgerService->updateAccountLedgerEntry(voucher_type_id: 19, date: $request->date, account_id: $updatePurchaseReturnProduct->tax_ac_id, trans_id: $updatePurchaseReturnProduct->id, amount: ($updatePurchaseReturnProduct->unit_tax_amount * $updatePurchaseReturnProduct->return_qty), amount_type: 'credit', branch_id: $updateReturn->branch_id);
                } else {

                    $this->accountLedgerService->deleteUnusedLedgerEntry(voucherType: 19, transId: $updatePurchaseReturnProduct->id, accountId: $updatePurchaseReturnProduct->current_tax_ac_id);
                }

                $index++;
            }

            if ($request->received_amount > 0) {

                $addAccountingVoucher = $this->accountingVoucherService->addAccountingVoucher(date: $request->date, voucherType: AccountingVoucherType::Receipt->value, remarks: $request->receipt_note, codeGenerator: $codeGenerator, voucherPrefix: $receiptVoucherPrefix, debitTotal: $request->received_amount, creditTotal: $request->received_amount, totalAmount: $request->received_amount, purchaseReturnRefId: $updateReturn->id);

                // Add Accounting Voucher Description Credit Entry
                $addAccountingVoucherDebitDescription = $this->accountingVoucherDescriptionService->addAccountingVoucherDescription(accountingVoucherId: $addAccountingVoucher->id, accountId: $request->account_id, paymentMethodId: null, amountType: 'dr', amount: $request->received_amount, note: null);

                //Add debit Ledger Entry
                $this->accountLedgerService->addAccountLedgerEntry(voucher_type_id: 8, date: $request->date, account_id: $request->account_id, trans_id: $addAccountingVoucherDebitDescription->id, amount: $request->received_amount, amount_type: 'debit');

                // Add Credit Account Accounting voucher Description
                $addAccountingVoucherCreditDescription = $this->accountingVoucherDescriptionService->addAccountingVoucherDescription(accountingVoucherId: $addAccountingVoucher->id, accountId: $request->supplier_account_id, paymentMethodId: $request->payment_method_id, amountType: 'cr', amount: $request->received_amount);

                // Add Accounting VoucherDescription References
                $this->accountingVoucherDescriptionReferenceService->addAccountingVoucherDescriptionReferences(accountingVoucherDescriptionId: $addAccountingVoucherCreditDescription->id, accountId: $request->supplier_account_id, amount: $request->received_amount, refIdColName: 'purchase_return_id', refIds: [$updateReturn->id]);

                //Add Credit Ledger Entry
                $this->accountLedgerService->addAccountLedgerEntry(voucher_type_id: 8, date: $request->date, account_id: $request->supplier_account_id, trans_id: $addAccountingVoucherCreditDescription->id, amount: $request->received_amount, amount_type: 'credit', cash_bank_account_id: $request->account_id);
            }

            $__index = 0;
            foreach ($request->product_ids as $productId) {

                $variantId = $request->variant_ids[$__index] != 'noid' ? $request->variant_ids[$__index] : null;
                $this->productStockService->adjustMainProductAndVariantStock(productId: $productId, variantId: $variantId);

                $this->productStockService->adjustBranchAllStock(productId: $productId, variantId: $variantId, branchId: $updateReturn->branch_id);

                if (isset($request->warehouse_ids[$__index])) {

                    $this->productStockService->adjustWarehouseStock(productId: $productId, variantId: $variantId, warehouseId: $request->warehouse_ids[$__index]);
                } else {

                    $this->productStockService->adjustBranchStock($productId, $variantId, branchId: $updateReturn->branch_id);
                }

                $__index++;
            }

            $deletedUnusedPurchaseReturnProducts = $this->purchaseReturnProductService->purchaseReturnProducts()->where('purchase_return_id', $updateReturn->id)->where('is_delete_in_update', 1)->get();
            if (count($deletedUnusedPurchaseReturnProducts) > 0) {

                foreach ($deletedUnusedPurchaseReturnProducts as $deletedUnusedPurchaseReturnProduct) {

                    $deletedUnusedPurchaseReturnProduct->delete();

                    // Adjust deleted product stock
                    $this->productStockService->adjustMainProductAndVariantStock($deletedUnusedPurchaseReturnProduct->product_id, $deletedUnusedPurchaseReturnProduct->variant_id);

                    $this->productStockService->adjustBranchAllStock(productId: $deletedUnusedPurchaseReturnProduct->product_id, variantId: $deletedUnusedPurchaseReturnProduct->variant_id, branchId: $updateReturn->branch_id);

                    if (isset($deletedUnusedPurchaseReturnProduct->warehouse_id)) {

                        $this->productStockService->adjustWarehouseStock($deletedUnusedPurchaseReturnProduct->product_id, $deletedUnusedPurchaseReturnProduct->variant_id, $deletedUnusedPurchaseReturnProduct->warehouse_id);
                    } else {

                        $this->productStockService->adjustBranchStock($deletedUnusedPurchaseReturnProduct->product_id, $deletedUnusedPurchaseReturnProduct->variant_id, $updateReturn->branch_id);
                    }
                }
            }

            if ($request->purchase_id) {

                $purchase = $this->purchaseService->singlePurchase(id: $request->purchase_id);
                $this->purchaseService->adjustPurchaseInvoiceAmounts($purchase);
            }

            if ($storedCurrParentPurchaseId) {

                $purchase = $this->purchaseService->singlePurchase(id: $storedCurrParentPurchaseId);
                $this->purchaseService->adjustPurchaseInvoiceAmounts($purchase);
            }

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json(__("Purchase return updated Successfully."));
    }

    //Deleted purchase return
    public function delete($id)
    {
        try {

            DB::beginTransaction();

            $deletePurchaseReturn = $this->purchaseReturnService->deletePurchaseReturn(id: $id);

            if (isset($deletePurchaseReturn['pass']) && $deletePurchaseReturn['pass'] == false) {

                return response()->json(['errorMsg' => $deletePurchaseReturn['msg']]);
            }

            foreach ($deletePurchaseReturn->purchaseReturnProducts as $returnProduct) {

                $this->productStockService->adjustMainProductAndVariantStock($returnProduct->product_id, $returnProduct->variant_id);

                $this->productStockService->adjustBranchAllStock($returnProduct->product_id, $returnProduct->variant_id, $deletePurchaseReturn->branch_id);

                if ($returnProduct->warehouse_id) {

                    $this->productStockService->adjustWarehouseStock($returnProduct->product_id, $returnProduct->variant_id, $returnProduct->warehouse_id);
                } else {

                    $this->productStockService->adjustBranchStock($returnProduct->product_id, $returnProduct->variant_id, $deletePurchaseReturn->branch_id);
                }
            }

            if ($deletePurchaseReturn->purchase) {

                $this->purchaseService->adjustPurchaseInvoiceAmounts($deletePurchaseReturn->purchase);
            }

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json('Purchase return deleted successfully');
    }
}
