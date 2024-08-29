<?php

namespace App\Services\Purchases\MethodContainerServices;

use App\Enums\PurchaseStatus;
use App\Enums\DayBookVoucherType;
use Illuminate\Support\Facades\DB;
use App\Enums\AccountingVoucherType;
use App\Services\Branches\BranchService;
use App\Enums\AccountLedgerVoucherType;
use App\Enums\UserActivityLogActionType;
use App\Enums\UserActivityLogSubjectType;
use App\Services\Accounts\AccountService;
use App\Services\Accounts\DayBookService;
use App\Services\Purchases\PurchaseService;
use App\Services\Setups\PaymentMethodService;
use App\Services\Users\UserActivityLogService;
use App\Services\Accounts\AccountFilterService;
use App\Services\Accounts\AccountLedgerService;
use App\Services\Purchases\PurchaseOrderService;
use App\Services\Accounts\AccountingVoucherService;
use App\Services\Purchases\PurchaseOrderProductService;
use App\Services\Accounts\AccountingVoucherDescriptionService;
use App\Services\Accounts\AccountingVoucherDescriptionReferenceService;
use App\Interfaces\Purchases\PurchaseOrderControllerMethodContainersInterface;

class PurchaseOrderControllerMethodContainersService implements PurchaseOrderControllerMethodContainersInterface
{
    public function __construct(
        private PurchaseOrderService $purchaseOrderService,
        private PurchaseService $purchaseService,
        private PurchaseOrderProductService $purchaseOrderProductService,
        private AccountService $accountService,
        private AccountFilterService $accountFilterService,
        private BranchService $branchService,
        private PaymentMethodService $paymentMethodService,
        private DayBookService $dayBookService,
        private AccountingVoucherService $accountingVoucherService,
        private AccountingVoucherDescriptionService $accountingVoucherDescriptionService,
        private AccountingVoucherDescriptionReferenceService $accountingVoucherDescriptionReferenceService,
        private AccountLedgerService $accountLedgerService,
        private UserActivityLogService $userActivityLogService,
    ) {}

    public function indexMethodContainer(object $request, ?int $supplierAccountId = null): array|object
    {
        $data = [];
        if ($request->ajax()) {

            return $this->purchaseOrderService->purchaseOrdersTable(request: $request, supplierAccountId: $supplierAccountId);
        }

        $ownBranchIdOrParentBranchId = auth()->user()?->branch?->parent_branch_id ? auth()->user()?->branch?->parent_branch_id : auth()->user()->branch_id;

        $data['branches'] = $this->branchService->branches(with: ['parentBranch'])
            ->orderByRaw('COALESCE(branches.parent_branch_id, branches.id), branches.id')->get();

        $data['supplierAccounts'] = $this->accountService->customerAndSupplierAccounts(ownBranchIdOrParentBranchId: $ownBranchIdOrParentBranchId, sortingByGroupNumber: 'desc');

        return $data;
    }

    public function showMethodContainer(int $id): ?array
    {
        $data = [];
        $data['order'] = $this->purchaseOrderService->singlePurchaseOrder(id: $id, with: [
            'supplier:id,name,phone,address',
            'admin:id,prefix,name,last_name',
            'purchaseAccount:id,name',
            'purchaseOrderProducts',
            'purchaseOrderProducts.product',
            'purchaseOrderProducts.variant',
            'purchaseOrderProducts.unit:id,code_name,base_unit_id,base_unit_multiplier',
            'purchaseOrderProducts.unit.baseUnit:id,base_unit_id,code_name',

            'purchaseOrderProducts.purchaseProducts',
            'purchaseOrderProducts.purchaseProducts.unit:id,code_name,base_unit_id,base_unit_multiplier',
            'purchaseOrderProducts.purchaseProducts.unit.baseUnit:id,base_unit_id,code_name',
            'purchaseOrderProducts.purchaseProducts.purchase',

            'references:id,voucher_description_id,purchase_id,amount',
            'references.voucherDescription:id,accounting_voucher_id',
            'references.voucherDescription.accountingVoucher:id,voucher_no,date,voucher_type',
            'references.voucherDescription.accountingVoucher.voucherDescriptions:id,accounting_voucher_id,account_id,payment_method_id',
            'references.voucherDescription.accountingVoucher.voucherDescriptions.paymentMethod:id,name',
            'references.voucherDescription.accountingVoucher.voucherDescriptions.account:id,name,account_number,account_group_id,bank_id,bank_branch',
            'references.voucherDescription.accountingVoucher.voucherDescriptions.account.bank:id,name',
            'references.voucherDescription.accountingVoucher.voucherDescriptions.account.group:id,sub_sub_group_number',
        ]);

        return $data;
    }

    public function printMethodContainer(object $request, int $id): ?array
    {
        $data = [];
        $data['order'] = $this->purchaseOrderService->singlePurchaseOrder(id: $id, with: [
            'supplier:id,name,phone,address',
            'admin:id,prefix,name,last_name',
            'purchaseAccount:id,name',
            'purchaseOrderProducts',
            'purchaseOrderProducts.product',
            'purchaseOrderProducts.variant',
            'purchaseOrderProducts.unit:id,code_name,base_unit_id,base_unit_multiplier',
            'purchaseOrderProducts.unit.baseUnit:id,base_unit_id,code_name',
        ]);

        $data['printPageSize'] = $request->print_page_size;

        return $data;
    }

    public function printSupplierCopyMethodContainer(object $request, int $id): ?array
    {
        $data = [];

        $data['order'] = $this->purchaseOrderService->singlePurchaseOrder(id: $id, with: [
            'supplier:id,name,phone,address',
            'admin:id,prefix,name,last_name',
            'purchaseAccount:id,name',
            'purchaseOrderProducts',
            'purchaseOrderProducts.product',
            'purchaseOrderProducts.variant',
            'purchaseOrderProducts.unit:id,code_name,base_unit_id,base_unit_multiplier',
            'purchaseOrderProducts.unit.baseUnit:id,base_unit_id,code_name',
        ]);

        $data['printPageSize'] = $request->print_page_size;

        return $data;
    }

    public function createMethodContainer(object $codeGenerator): array
    {
        $data = [];

        $ownBranchIdOrParentBranchId = auth()->user()?->branch?->parent_branch_id ? auth()->user()?->branch?->parent_branch_id : auth()->user()->branch_id;

        $generalSettings = config('generalSettings');
        $orderPrefix = $generalSettings['prefix__purchase_order_prefix'] ? $generalSettings['prefix__purchase_order_prefix'] : 'PO';
        $data['orderId'] = $codeGenerator->generateMonthAndTypeWise(table: 'purchases', column: 'invoice_id', typeColName: 'purchase_status', typeValue: PurchaseStatus::PurchaseOrder->value, prefix: $orderPrefix, splitter: '-', suffixSeparator: '-', branchId: auth()->user()->branch_id);

        $accounts = $this->accountService->accounts(with: [
            'bank:id,name',
            'group:id,sorting_number,sub_sub_group_number',
            'bankAccessBranch',
        ])->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('branch_id', auth()->user()->branch_id)
            ->whereIn('account_groups.sub_sub_group_number', [2])
            ->select('accounts.id', 'accounts.name', 'accounts.account_number', 'accounts.bank_id', 'accounts.account_group_id')
            ->orWhereIn('account_groups.sub_sub_group_number', [1, 11])
            ->get();

        $data['accounts'] = $this->accountFilterService->filterCashBankAccounts($accounts);

        $data['methods'] = $this->paymentMethodService->paymentMethods(with: ['paymentMethodSetting'])->get();

        $data['purchaseAccounts'] = $this->accountService->accounts()
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.sub_group_number', 12)
            ->where('accounts.branch_id', auth()->user()->branch_id)
            ->get(['accounts.id', 'accounts.name']);

        $data['taxAccounts'] = $this->accountService->accounts()
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.sub_sub_group_number', 8)
            ->where('accounts.branch_id', auth()->user()->branch_id)
            ->get(['accounts.id', 'accounts.name', 'tax_percent']);

        $data['supplierAccounts'] = $this->accountService->customerAndSupplierAccounts(ownBranchIdOrParentBranchId: $ownBranchIdOrParentBranchId, sortingByGroupNumber: 'desc');

        return $data;
    }

    public function storeMethodContainer(object $request, object $codeGenerator): array
    {
        $generalSettings = config('generalSettings');
        $invoicePrefix = $generalSettings['prefix__purchase_order_prefix'] ? $generalSettings['prefix__purchase_order_prefix'] : 'PO';
        $paymentVoucherPrefix = $generalSettings['prefix__payment_voucher_prefix'] ? $generalSettings['prefix__payment_voucher_prefix'] : 'PV';
        $isEditProductPrice = $generalSettings['purchase__is_edit_pro_price'];

        $restrictions = $this->purchaseOrderService->restrictions($request);
        if ($restrictions['pass'] == false) {

            return ['pass' => false, 'msg' => $restrictions['msg']];
        }

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
        $this->userActivityLogService->addLog(action: UserActivityLogActionType::Added->value, subjectType: UserActivityLogSubjectType::PurchaseOrder->value, dataObj: $addPurchaseOrder);

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

        $printPageSize = $request->print_page_size;
        $payingAmount = $request->paying_amount;

        return ['order' => $order, 'printPageSize' => $printPageSize, 'payingAmount' => $payingAmount];
    }

    public function editMethodContainer(int $id): array
    {
        $data = [];
        $order = $this->purchaseOrderService->singlePurchaseOrder(id: $id, with: [
            'branch',
            'branch.parentBranch',
            'supplier',
            'supplier.group',
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
            'bankAccessBranch',
        ])->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('branch_id', $order->branch_id)
            ->whereIn('account_groups.sub_sub_group_number', [2])
            ->select('accounts.id', 'accounts.name', 'accounts.account_number', 'accounts.bank_id', 'accounts.account_group_id')
            ->orWhereIn('account_groups.sub_sub_group_number', [1, 11])
            ->get();

        $data['accounts'] = $this->accountFilterService->filterCashBankAccounts($accounts);

        $data['methods'] = $this->paymentMethodService->paymentMethods(with: ['paymentMethodSetting'])->get();

        $data['purchaseAccounts'] = $this->accountService->accounts()
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.sub_group_number', 12)
            ->where('accounts.branch_id', $order->branch_id)
            ->get(['accounts.id', 'accounts.name']);

        $data['taxAccounts'] = $this->accountService->accounts()
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.sub_sub_group_number', 8)
            // ->where('accounts.branch_id', $order->branch_id)
            ->get(['accounts.id', 'accounts.name', 'tax_percent']);

        $data['supplierAccounts'] = $this->accountService->customerAndSupplierAccounts(ownBranchIdOrParentBranchId: $ownBranchIdOrParentBranchId, sortingByGroupNumber: 'desc');

        $data['order'] = $order;
        $data['ownBranchIdOrParentBranchId'] = $ownBranchIdOrParentBranchId;

        return $data;
    }

    public function updateMethodContainer(int $id, object $request, object $codeGenerator): ?array
    {
        $generalSettings = config('generalSettings');
        $paymentVoucherPrefix = $paymentVoucherPrefix = $generalSettings['prefix__payment_voucher_prefix'] ? $generalSettings['prefix__payment_voucher_prefix'] : 'PV';
        $isEditProductPrice = $generalSettings['purchase__is_edit_pro_price'];

        $restrictions = $this->purchaseOrderService->restrictions(request: $request, checkSupplierChangeRestriction: true, purchaseOrderId: $id);
        if ($restrictions['pass'] == false) {

            return ['pass' => false, 'msg' => $restrictions['msg']];
        }

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

        // Add user Log
        $this->userActivityLogService->addLog(action: UserActivityLogActionType::Updated->value, subjectType: UserActivityLogSubjectType::PurchaseOrder->value, dataObj: $updatePurchaseOrder);

        return null;
    }

    public function deleteMethodContainer(int $id): ?array
    {
        $deletePurchaseOrder = $this->purchaseOrderService->deletePurchaseOrder(id: $id);

        if (isset($deletePurchaseOrder['pass']) && $deletePurchaseOrder['pass'] == false) {

            return ['pass' => false, 'msg' => $deletePurchaseOrder['msg']];
        }

        // Add user Log
        $this->userActivityLogService->addLog(action: UserActivityLogActionType::Deleted->value, subjectType: UserActivityLogSubjectType::PurchaseOrder->value, dataObj: $deletePurchaseOrder);

        return null;
    }

    public function searchByPoIdMethodContainer(int $keyWord): array|object
    {
        $orders = DB::table('purchases')
            ->leftJoin('accounts', 'purchases.supplier_account_id', 'accounts.id')
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('purchases.invoice_id', 'like', "%{$keyWord}%")
            ->where('purchases.branch_id', auth()->user()->branch_id)
            ->where('purchases.purchase_status', PurchaseStatus::PurchaseOrder->value)
            ->select(
                'purchases.id as purchase_order_id',
                'purchases.invoice_id as po_id',
                'purchases.supplier_account_id',
                'accounts.name as supplier_name',
                'accounts.phone as supplier_phone',
                'account_groups.default_balance_type',
            )->limit(35)->get();

        if (count($orders) > 0) {

            return view('search_results_view.purchase_order_search_result_list', compact('orders'));
        } else {

            return ['noResult' => 'no result'];
        }
    }

    public function poIdMethodContainer(object $codeGenerator): string
    {
        $generalSettings = config('generalSettings');
        $orderPrefix = $generalSettings['prefix__purchase_order_prefix'] ? $generalSettings['prefix__purchase_order_prefix'] : 'PO';
        return $codeGenerator->generateMonthAndTypeWise(table: 'purchases', column: 'invoice_id', typeColName: 'purchase_status', typeValue: PurchaseStatus::PurchaseOrder->value, prefix: $orderPrefix, splitter: '-', suffixSeparator: '-', branchId: auth()->user()->branch_id);
    }
}
