<?php

namespace App\Http\Controllers\Purchases;

use Illuminate\Http\Request;
use App\Enums\DayBookVoucherType;
use App\Utils\UserActivityLogUtil;
use Illuminate\Support\Facades\DB;
use App\Enums\AccountingVoucherType;
use App\Http\Controllers\Controller;
use App\Services\Setups\BranchService;
use App\Enums\AccountLedgerVoucherType;
use App\Services\CodeGenerationService;
use App\Services\Accounts\AccountService;
use App\Services\Accounts\DayBookService;
use App\Services\Purchases\PurchaseService;
use App\Services\Setups\BranchSettingService;
use App\Services\Setups\PaymentMethodService;
use App\Services\Accounts\AccountFilterService;
use App\Services\Accounts\AccountLedgerService;
use App\Services\Purchases\PurchaseOrderService;
use App\Services\Accounts\AccountingVoucherService;
use App\Services\Purchases\PurchaseOrderProductService;
use App\Services\Accounts\AccountingVoucherDescriptionService;
use App\Services\Accounts\AccountingVoucherDescriptionReferenceService;

class PurchaseOrderController extends Controller
{
    public function __construct(
        private PurchaseOrderService $purchaseOrderService,
        private PurchaseService $purchaseService,
        private PurchaseOrderProductService $purchaseOrderProductService,
        private AccountService $accountService,
        private AccountFilterService $accountFilterService,
        private BranchService $branchService,
        private BranchSettingService $branchSettingService,
        private PaymentMethodService $paymentMethodService,
        private DayBookService $dayBookService,
        private AccountingVoucherService $accountingVoucherService,
        private AccountingVoucherDescriptionService $accountingVoucherDescriptionService,
        private AccountingVoucherDescriptionReferenceService $accountingVoucherDescriptionReferenceService,
        private AccountLedgerService $accountLedgerService,
        private UserActivityLogUtil $userActivityLogUtil,
    ) {
    }

    public function index(Request $request, $supplierAccountId = null)
    {
        if (!auth()->user()->can('purchase_all')) {

            abort(403, 'Access Forbidden.');
        }

        if ($request->ajax()) {

            return $this->purchaseOrderService->purchaseOrdersTable(request: $request, supplierAccountId: $supplierAccountId);
        }

        $ownBranchIdOrParentBranchId = auth()->user()?->branch?->parent_branch_id ? auth()->user()?->branch?->parent_branch_id : auth()->user()->branch_id;

        $branches = $this->branchService->branches(with: ['parentBranch'])
            ->orderByRaw('COALESCE(branches.parent_branch_id, branches.id), branches.id')->get();

        $supplierAccounts = $this->accountService->customerAndSupplierAccounts($ownBranchIdOrParentBranchId);

        return view('purchase.orders.index', compact('branches', 'supplierAccounts'));
    }

    public function show($id)
    {
        $order = $this->purchaseOrderService->singlePurchaseOrder(id: $id, with: [
            'supplier:id,name,phone,address',
            'admin:id,prefix,name,last_name',
            'purchaseAccount:id,name',
            'purchaseOrderProducts',
            'purchaseOrderProducts.product',
            'purchaseOrderProducts.variant',
            'purchaseOrderProducts.unit:id,code_name,base_unit_id,base_unit_multiplier',
            'purchaseOrderProducts.unit.baseUnit:id,base_unit_id,code_name',

            'references:id,voucher_description_id,purchase_id,amount',
            'references.voucherDescription:id,accounting_voucher_id',
            'references.voucherDescription.accountingVoucher:id,voucher_no,date,voucher_type',
            'references.voucherDescription.accountingVoucher.voucherDescriptions:id,accounting_voucher_id,account_id,payment_method_id',
            'references.voucherDescription.accountingVoucher.voucherDescriptions.paymentMethod:id,name',
            'references.voucherDescription.accountingVoucher.voucherDescriptions.account:id,name,account_number,account_group_id,bank_id,bank_branch',
            'references.voucherDescription.accountingVoucher.voucherDescriptions.account.bank:id,name',
            'references.voucherDescription.accountingVoucher.voucherDescriptions.account.group:id,sub_sub_group_number',
        ]);

        return view('purchase.orders.ajax_view.show', compact('order'));
    }

    public function printSupplierCopy($id)
    {
        $order = $this->purchaseOrderService->singlePurchaseOrder(id: $id, with: [
            'supplier:id,name,phone,address',
            'admin:id,prefix,name,last_name',
            'purchaseAccount:id,name',
            'purchaseOrderProducts',
            'purchaseOrderProducts.product',
            'purchaseOrderProducts.variant',
            'purchaseOrderProducts.unit:id,code_name,base_unit_id,base_unit_multiplier',
            'purchaseOrderProducts.unit.baseUnit:id,base_unit_id,code_name',
        ]);

        return view('purchase.orders.ajax_view.print_supplier_copy', compact('order'));
    }

    public function create()
    {
        $ownBranchIdOrParentBranchId = auth()->user()?->branch?->parent_branch_id ? auth()->user()?->branch?->parent_branch_id : auth()->user()->branch_id;

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

        $taxAccounts = $this->accountService->accounts()
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.sub_sub_group_number', 8)
            ->where('accounts.branch_id', auth()->user()->branch_id)
            ->get(['accounts.id', 'accounts.name', 'tax_percent']);

        $supplierAccounts = $this->accountService->customerAndSupplierAccounts($ownBranchIdOrParentBranchId);

        return view('purchase.orders.create', compact('methods', 'accounts', 'purchaseAccounts', 'taxAccounts', 'supplierAccounts'));
    }

    public function store(Request $request, CodeGenerationService $codeGenerator)
    {
        $this->validate($request, [
            'supplier_account_id' => 'required',
            'date' => 'required|date',
            'delivery_date' => 'required|date',
            'payment_method_id' => 'required',
            'purchase_account_id' => 'required',
            'account_id' => 'required',
        ], [
            'purchase_account_id.required' => 'Purchase A/c is required.',
            'account_id.required' => 'Credit A/c is required.',
            'payment_method_id.required' => 'Payment method field is required.',
            'supplier_id.required' => 'Supplier is required.',
        ]);

        $restrictions = $this->purchaseOrderService->restrictions($request);
        if ($restrictions['pass'] == false) {

            return response()->json(['errorMsg' => $restrictions['msg']]);
        }

        try {

            DB::beginTransaction();

            $generalSettings = config('generalSettings');
            $branchSetting = $this->branchSettingService->singleBranchSetting(branchId: auth()->user()->branch_id);
            $invoicePrefix = isset($branchSetting) && $branchSetting?->purchase_order_prefix ? $branchSetting?->purchase_order_prefix : 'PO';
            $paymentVoucherPrefix = isset($branchSetting) && $branchSetting?->payment_voucher_prefix ? $branchSetting?->payment_voucher_prefix : $generalSettings['prefix__payment'];
            $isEditProductPrice = $generalSettings['purchase__is_edit_pro_price'];

            $addPurchaseOrder = $this->purchaseOrderService->addPurchaseOrder(request: $request, codeGenerator: $codeGenerator, invoicePrefix: $invoicePrefix);

            // Add Day Book entry for Purchase
            $this->dayBookService->addDayBook(voucherTypeId: DayBookVoucherType::PurchaseOrder->value, date: $request->date, accountId: $request->supplier_account_id, transId: $addPurchaseOrder->id, amount: $request->total_ordered_amount, amountType: 'credit');

            $addPurchaseOrderProduct = $this->purchaseOrderProductService->addPurchaseOrderProduct(request: $request, isEditProductPrice: $isEditProductPrice, purchaseOrderId: $addPurchaseOrder->id);

            if ($request->paying_amount > 0) {

                $addAccountingVoucher = $this->accountingVoucherService->addAccountingVoucher(date: $request->date, voucherType: AccountingVoucherType::Payment->value, remarks: null, codeGenerator: $codeGenerator, voucherPrefix: $paymentVoucherPrefix, debitTotal: $request->paying_amount, creditTotal: $request->paying_amount, totalAmount: $request->paying_amount, purchaseRefId: $addPurchaseOrder->id);

                // Add Debit Account Accounting voucher Description
                $addAccountingVoucherDebitDescription = $this->accountingVoucherDescriptionService->addAccountingVoucherDescription(accountingVoucherId: $addAccountingVoucher->id, accountId: $request->supplier_account_id, paymentMethodId: null, amountType: 'dr', amount: $request->paying_amount);

                // Add Accounting VoucherDescription References
                $this->accountingVoucherDescriptionReferenceService->addAccountingVoucherDescriptionReferences(accountingVoucherDescriptionId: $addAccountingVoucherDebitDescription->id, accountId: $request->supplier_account_id, amount: $request->paying_amount, refIdColName: 'purchase_id', refIds: [$addPurchaseOrder->id]);

                //Add Debit Ledger Entry
                $this->accountLedgerService->addAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::Payment->value, date: $request->date, account_id: $request->supplier_account_id, trans_id: $addAccountingVoucherDebitDescription->id, amount: $request->paying_amount, amount_type: 'debit', cash_bank_account_id: $request->account_id);

                // Add Payment Description Credit Entry
                $addAccountingVoucherDebitDescription = $this->accountingVoucherDescriptionService->addAccountingVoucherDescription(accountingVoucherId: $addAccountingVoucher->id, accountId: $request->account_id, paymentMethodId: $request->payment_method_id, amountType: 'cr', amount: $request->paying_amount, note: $request->payment_note);

                //Add Credit Ledger Entry
                $this->accountLedgerService->addAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::Payment->value, date: $request->date, account_id: $request->account_id, trans_id: $addAccountingVoucherDebitDescription->id, amount: $request->paying_amount, amount_type: 'credit');
            }

            // Add user Log
            $this->userActivityLogUtil->addLog(action: 1, subject_type: 5, data_obj: $addPurchaseOrder);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        if ($request->action == 2) {

            return response()->json(['successMsg' => __("Purchase order is created successfully.")]);
        } else {

            $order = $this->purchaseOrderService->singlePurchaseOrder(
                with: [
                    'branch',
                    'branch.parentBranch',
                    'supplier',
                    'admin:id,prefix,name,last_name',
                    'purchaseOrderProducts',
                    'purchaseOrderProducts.product',
                    'purchaseOrderProducts.variant',
                    'purchaseOrderProducts.unit:id,code_name',
                ],
                id: $addPurchaseOrder->id
            );

            $payingAmount = $request->paying_amount;
            return view('purchase.save_and_print_template.print_purchase_order', compact('order', 'payingAmount'));
        }
    }

    public function edit($id)
    {
        if (!auth()->user()->can('purchase_edit')) {

            abort(403, 'Access Forbidden.');
        }

        $order = $this->purchaseOrderService->singlePurchaseOrder(id: $id, with: [
            'branch',
            'branch.parentBranch',
            'purchaseOrderProducts',
            'purchaseOrderProducts.product',
            'purchaseOrderProducts.variant',
            'purchaseOrderProducts.product.unit:id,name,code_name',
            'purchaseOrderProducts.product.unit.childUnits:id,name,code_name,base_unit_id,base_unit_multiplier',
            'purchaseOrderProducts.unit:id,name,code_name,base_unit_multiplier',
        ]);

        $ownBranchIdOrParentBranchId = $order?->branch?->parent_branch_id ? $order?->branch?->parent_branch_id : $order->branch_id;

        $accounts = $this->accountService->accounts(with: [
            'bank:id,name',
            'group:id,sorting_number,sub_sub_group_number',
            'bankAccessBranch'
        ])->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('branch_id', $order->branch_id)
            ->whereIn('account_groups.sub_sub_group_number', [2])
            ->select('accounts.id', 'accounts.name', 'accounts.account_number', 'accounts.bank_id', 'accounts.account_group_id')
            ->orWhereIn('account_groups.sub_sub_group_number', [1, 11])
            ->get();

        $accounts = $this->accountFilterService->filterCashBankAccounts($accounts);

        $methods = $this->paymentMethodService->paymentMethods(with: ['paymentMethodSetting'])->get();

        $purchaseAccounts = $this->accountService->accounts()
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.sub_group_number', 12)
            ->where('accounts.branch_id', $order->branch_id)
            ->get(['accounts.id', 'accounts.name']);

        $taxAccounts = $this->accountService->accounts()
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.sub_sub_group_number', 8)
            // ->where('accounts.branch_id', $order->branch_id)
            ->get(['accounts.id', 'accounts.name', 'tax_percent']);

        $supplierAccounts = $this->accountService->customerAndSupplierAccounts($ownBranchIdOrParentBranchId);

        return view('purchase.orders.edit', compact('methods', 'accounts', 'purchaseAccounts', 'taxAccounts', 'supplierAccounts', 'order', 'ownBranchIdOrParentBranchId'));
    }

    public function update($id, Request $request, CodeGenerationService $codeGenerator)
    {
        $this->validate($request, [
            'supplier_account_id' => 'required',
            'date' => 'required|date',
            'delivery_date' => 'required|date',
            'payment_method_id' => 'required',
            'purchase_account_id' => 'required',
            'account_id' => 'required',
        ], [
            'purchase_account_id.required' => 'Purchase A/c is required.',
            'account_id.required' => 'Credit A/c is required.',
            'payment_method_id.required' => 'Payment method field is required.',
            'supplier_id.required' => 'Supplier is required.',
        ]);

        $restrictions = $this->purchaseOrderService->restrictions(request: $request, checkSupplierChangeRestriction: true, purchaseOrderId: $id);
        if ($restrictions['pass'] == false) {

            return response()->json(['errorMsg' => $restrictions['msg']]);
        }

        try {

            DB::beginTransaction();

            $generalSettings = config('generalSettings');
            $branchSetting = $this->branchSettingService->singleBranchSetting(branchId: auth()->user()->branch_id);
            $paymentVoucherPrefix = isset($branchSetting) && $branchSetting?->payment_voucher_prefix ? $branchSetting?->payment_voucher_prefix : $generalSettings['prefix__payment'];
            $isEditProductPrice = $generalSettings['purchase__is_edit_pro_price'];

            // get updatable purchase row
            $purchaseOrder = $this->purchaseService->singlePurchase(id: $id, with: ['purchaseOrderProducts']);

            $storedCurrPurchaseAccountId = $purchaseOrder->purchase_account_id;
            $storedCurrSupplierAccountId = $purchaseOrder->supplier_account_id;
            $storedCurrPurchaseTaxAccountId = $purchaseOrder->purchase_tax_ac_id;
            $storePurchaseProducts = $purchaseOrder->purchaseOrderProducts;

            $updatePurchaseOrder = $this->purchaseOrderService->updatePurchaseOrder(request: $request, updatePurchaseOrder: $purchaseOrder);
            $this->purchaseService->adjustPurchaseInvoiceAmounts(purchase: $updatePurchaseOrder);

            // Add Day Book entry for Purchase
            $this->dayBookService->updateDayBook(voucherTypeId: DayBookVoucherType::PurchaseOrder->value, date: $request->date, accountId: $request->supplier_account_id, transId: $updatePurchaseOrder->id, amount: $request->total_ordered_amount, amountType: 'credit');

            $this->purchaseOrderProductService->updatePurchaseOrderProducts(request: $request, isEditProductPrice: $isEditProductPrice, purchaseOrderId: $updatePurchaseOrder->id);

            $deletedUnusedPurchaseOrderProducts = $this->purchaseOrderProductService->purchaseOrderProducts()
                ->where('purchase_id', $updatePurchaseOrder->id)
                ->where('is_delete_in_update', 1)->get();

            if (count($deletedUnusedPurchaseOrderProducts) > 0) {

                foreach ($deletedUnusedPurchaseOrderProducts as $deletedPurchaseOrderProduct) {

                    $storedProductId = $deletedPurchaseOrderProduct->product_id;
                    $storedVariantId = $deletedPurchaseOrderProduct->variant_id;
                    $deletedPurchaseOrderProduct->delete();
                }
            }

            if ($request->paying_amount > 0) {

                $addAccountingVoucher = $this->accountingVoucherService->addAccountingVoucher(date: $request->date, voucherType: AccountingVoucherType::Payment->value, remarks: null, codeGenerator: $codeGenerator, voucherPrefix: $paymentVoucherPrefix, debitTotal: $request->paying_amount, creditTotal: $request->paying_amount, totalAmount: $request->paying_amount, purchaseRefId: $updatePurchaseOrder->id);

                // Add Debit Account Accounting voucher Description
                $addAccountingVoucherDebitDescription = $this->accountingVoucherDescriptionService->addAccountingVoucherDescription(accountingVoucherId: $addAccountingVoucher->id, accountId: $request->supplier_account_id, paymentMethodId: null, amountType: 'dr', amount: $request->paying_amount);

                // Add Accounting VoucherDescription References
                $this->accountingVoucherDescriptionReferenceService->addAccountingVoucherDescriptionReferences(accountingVoucherDescriptionId: $addAccountingVoucherDebitDescription->id, accountId: $request->supplier_account_id, amount: $request->paying_amount, refIdColName: 'purchase_id', refIds: [$updatePurchaseOrder->id]);

                //Add Debit Ledger Entry
                $this->accountLedgerService->addAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::Payment->value, date: $request->date, account_id: $request->supplier_account_id, trans_id: $addAccountingVoucherDebitDescription->id, amount: $request->paying_amount, amount_type: 'debit', cash_bank_account_id: $request->account_id);

                // Add Payment Description Credit Entry
                $addAccountingVoucherDebitDescription = $this->accountingVoucherDescriptionService->addAccountingVoucherDescription(accountingVoucherId: $addAccountingVoucher->id, accountId: $request->account_id, paymentMethodId: $request->payment_method_id, amountType: 'cr', amount: $request->paying_amount, note: $request->payment_note);

                //Add Credit Ledger Entry
                $this->accountLedgerService->addAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::Payment->value, date: $request->date, account_id: $request->account_id, trans_id: $addAccountingVoucherDebitDescription->id, amount: $request->paying_amount, amount_type: 'credit');
            }

            $this->userActivityLogUtil->addLog(action: 2, subject_type: 5, data_obj: $updatePurchaseOrder);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        session()->flash('successMsg', __('Purchase order is updated successfully'));

        return response()->json(__('Purchase order is updated successfully'));
    }

    // delete purchase method
    public function delete($id)
    {
        try {
            DB::beginTransaction();

            $deletePurchaseOrder = $this->purchaseOrderService->deletePurchaseOrder(id: $id);

            if (isset($deletePurchaseOrder['pass']) && $deletePurchaseOrder['pass'] == false) {

                return response()->json(['errorMsg' => $deletePurchaseOrder['msg']]);
            }

            // Add user Log
            $this->userActivityLogUtil->addLog(action: 3, subject_type: 5, data_obj: $deletePurchaseOrder);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json(__("Purchase order is deleted successfully"));
    }
}
