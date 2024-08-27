<?php

namespace App\Services\Sales\MethodContainerServices;

use App\Enums\SaleStatus;
use App\Enums\BooleanType;
use App\Enums\DayBookVoucherType;
use Illuminate\Support\Facades\DB;
use App\Services\Sales\SaleService;
use App\Enums\AccountingVoucherType;
use App\Services\Branches\BranchService;
use App\Enums\AccountLedgerVoucherType;
use App\Enums\UserActivityLogActionType;
use App\Enums\UserActivityLogSubjectType;
use App\Services\Accounts\AccountService;
use App\Services\Accounts\DayBookService;
use App\Services\Sales\SalesOrderService;
use App\Services\Sales\SaleProductService;
use App\Services\Products\PriceGroupService;
use App\Services\Setups\PaymentMethodService;
use App\Services\Users\UserActivityLogService;
use App\Services\Accounts\AccountFilterService;
use App\Services\Accounts\AccountLedgerService;
use App\Services\Sales\SalesOrderProductService;
use App\Services\Products\ManagePriceGroupService;
use App\Services\Accounts\AccountingVoucherService;
use App\Services\Accounts\AccountingVoucherDescriptionService;
use App\Interfaces\Sales\SalesOrderControllerMethodContainersInterface;
use App\Services\Accounts\AccountingVoucherDescriptionReferenceService;

class SalesOrderControllerMethodContainersService implements SalesOrderControllerMethodContainersInterface
{
    public function __construct(
        private SaleService $saleService,
        private SalesOrderService $salesOrderService,
        private SaleProductService $saleProductService,
        private SalesOrderProductService $salesOrderProductService,
        private DayBookService $dayBookService,
        private AccountService $accountService,
        private AccountLedgerService $accountLedgerService,
        private AccountFilterService $accountFilterService,
        private PaymentMethodService $paymentMethodService,
        private BranchService $branchService,
        private PriceGroupService $priceGroupService,
        private ManagePriceGroupService $managePriceGroupService,
        private AccountingVoucherService $accountingVoucherService,
        private AccountingVoucherDescriptionService $accountingVoucherDescriptionService,
        private AccountingVoucherDescriptionReferenceService $accountingVoucherDescriptionReferenceService,
        private UserActivityLogService $userActivityLogService,
    ) {}

    public function indexMethodContainer(object $request, ?int $customerAccountId): array|object
    {
        $data = [];
        if ($request->ajax()) {

            return $this->salesOrderService->salesOrderListTable(request: $request, customerAccountId: $customerAccountId);
        }

        $ownBranchIdOrParentBranchId = auth()->user()?->branch?->parent_branch_id ? auth()->user()?->branch?->parent_branch_id : auth()->user()->branch_id;

        $data['branches'] = $this->branchService->branches(with: ['parentBranch'])
            ->orderByRaw('COALESCE(branches.parent_branch_id, branches.id), branches.id')->get();

        $data['customerAccounts'] = $this->accountService->customerAndSupplierAccounts($ownBranchIdOrParentBranchId);

        return $data;
    }

    public function showMethodContainer(int $id): array
    {
        $data = [];
        $order = $this->salesOrderService->singleSalesOrder(id: $id, with: [
            'customer:id,name,phone,address',
            'createdBy:id,prefix,name,last_name',
            'saleProducts',
            'saleProducts.product',
            'saleProducts.variant',
            'saleProducts.branch:id,name,branch_code,area_name,parent_branch_id',
            'saleProducts.branch.parentBranch:id,name,branch_code,area_name',
            'saleProducts.warehouse:id,warehouse_name,warehouse_code',
            'saleProducts.unit:id,code_name,base_unit_id,base_unit_multiplier',
            'saleProducts.unit.baseUnit:id,base_unit_id,code_name',

            'references:id,voucher_description_id,sale_id,amount',
            'references.voucherDescription:id,accounting_voucher_id',
            'references.voucherDescription.accountingVoucher:id,voucher_no,date,voucher_type',
            'references.voucherDescription.accountingVoucher.voucherDescriptions:id,accounting_voucher_id,account_id,payment_method_id',
            'references.voucherDescription.accountingVoucher.voucherDescriptions.paymentMethod:id,name',
            'references.voucherDescription.accountingVoucher.voucherDescriptions.account:id,name,account_number,account_group_id,bank_id,bank_branch',
            'references.voucherDescription.accountingVoucher.voucherDescriptions.account.bank:id,name',
            'references.voucherDescription.accountingVoucher.voucherDescriptions.account.group:id,sub_sub_group_number',
        ]);

        $data['customerCopySaleProducts'] = $this->saleProductService->customerCopySaleProducts(saleId: $order->id);
        $data['order'] = $order;

        return $data;
    }

    public function editMethodContainer(int $id): array
    {
        $order = $this->salesOrderService->singleSalesOrder(id: $id, with: [
            'customer',
            'customer.group',
            'saleProducts',
            'saleProducts.product',
            'saleProducts.variant',
            'saleProducts.warehouse:id,warehouse_name,warehouse_code',
            'saleProducts.unit:id,name,code_name,base_unit_id,base_unit_multiplier',
            'saleProducts.unit.baseUnit:id,name,code_name,base_unit_id',
        ]);

        $generalSettings = config('generalSettings');
        $ownBranchIdOrParentBranchId = $order?->branch?->parent_branch_id ? $order?->branch?->parent_branch_id : $order->branch_id;

        $accounts = $this->accountService->accounts(with: [
            'bank:id,name',
            'group:id,sorting_number,sub_sub_group_number',
            'bankAccessBranch',
        ])->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('branch_id', $order->branch_id)
            ->whereIn('account_groups.sub_sub_group_number', [2])
            ->select('accounts.id', 'accounts.name', 'accounts.account_number', 'accounts.bank_id', 'accounts.account_group_id')
            ->orWhereIn('account_groups.sub_sub_group_number', [1, 11])->get();

        $data['accounts'] = $this->accountFilterService->filterCashBankAccounts($accounts);

        $data['methods'] = $this->paymentMethodService->paymentMethods(with: ['paymentMethodSetting'])->get();

        $data['saleAccounts'] = $this->accountService->accounts()
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.sub_group_number', 15)
            ->where('accounts.branch_id', $order->branch_id)
            ->get(['accounts.id', 'accounts.name']);

        $data['taxAccounts'] = $this->accountService->accounts()
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.sub_sub_group_number', 8)
            // ->where('accounts.branch_id', $order->branch_id)
            ->get(['accounts.id', 'accounts.name', 'tax_percent']);

        $data['customerAccounts'] = $this->accountService->customerAndSupplierAccounts($ownBranchIdOrParentBranchId);

        $data['priceGroups'] = $this->priceGroupService->priceGroups()->get(['id', 'name']);

        $data['priceGroupProducts'] = $this->managePriceGroupService->priceGroupProducts();

        $data['order'] = $order;

        return $data;
    }

    public function updateMethodContainer(int $id, object $request, object $codeGenerator): ?array
    {
        $restrictions = $this->saleService->restrictions(request: $request, accountService: $accountService, checkCustomerChangeRestriction: true, saleId: $id);

        if ($restrictions['pass'] == false) {

            return ['pass' => false, 'msg' => $restrictions['msg']];
        }

        $generalSettings = config('generalSettings');
        $receiptVoucherPrefix = $generalSettings['prefix__receipt_voucher_prefix'] ? $generalSettings['prefix__receipt_voucher_prefix'] : 'RV';
        $stockAccountingMethod = $generalSettings['business_or_shop__stock_accounting_method'];

        $order = $this->salesOrderService->singleSalesOrder(id: $id, with: ['saleProducts']);

        $storedCurrSaleAccountId = $order->sale_account_id;
        $storedCurrCustomerAccountId = $order->customer_account_id;
        $storedCurrSaleTaxAccountId = $order->sale_tax_ac_id;

        $updateSalesOrder = $this->salesOrderService->updateSalesOrder(request: $request, updateSalesOrder: $order);

        // Update Day Book entry for Sale
        $this->dayBookService->updateDayBook(voucherTypeId: DayBookVoucherType::SalesOrder->value, date: $request->date, accountId: $request->customer_account_id, transId: $updateSalesOrder->id, amount: $request->total_invoice_amount, amountType: 'debit');

        $updateSalesOrderProducts = $this->salesOrderProductService->updateSalesOrderProducts(request: $request, salesOrder: $updateSalesOrder);

        if ($request->received_amount > 0) {

            $addAccountingVoucher = $this->accountingVoucherService->addAccountingVoucher(date: $request->date, voucherType: AccountingVoucherType::Payment->value, remarks: $request->payment_note, codeGenerator: $codeGenerator, voucherPrefix: $receiptVoucherPrefix, debitTotal: $request->received_amount, creditTotal: $request->received_amount, totalAmount: $request->received_amount, saleRefId: $updateSalesOrder->id);

            // Add Debit Account Accounting voucher Description
            $addAccountingVoucherDebitDescription = $this->accountingVoucherDescriptionService->addAccountingVoucherDescription(accountingVoucherId: $addAccountingVoucher->id, accountId: $request->account_id, paymentMethodId: $request->payment_method_id, amountType: 'dr', amount: $request->received_amount);

            //Add Debit Ledger Entry
            $this->accountLedgerService->addAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::Receipt->value, date: $request->date, account_id: $request->account_id, trans_id: $addAccountingVoucherDebitDescription->id, amount: $request->received_amount, amount_type: 'debit');

            // Add Payment Description Credit Entry
            $this->addAccountingVoucherCreditDescription = $accountingVoucherDescriptionService->addAccountingVoucherDescription(accountingVoucherId: $addAccountingVoucher->id, accountId: $request->customer_account_id, paymentMethodId: null, amountType: 'cr', amount: $request->received_amount, note: $request->payment_note);

            // Add Accounting VoucherDescription References
            $this->accountingVoucherDescriptionReferenceService->addAccountingVoucherDescriptionReferences(accountingVoucherDescriptionId: $addAccountingVoucherCreditDescription->id, accountId: $request->customer_account_id, amount: $request->received_amount, refIdColName: 'sale_id', refIds: [$updateSalesOrder->id]);

            //Add Credit Ledger Entry
            $this->accountLedgerService->addAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::Receipt->value, date: $request->date, account_id: $request->customer_account_id, trans_id: $addAccountingVoucherCreditDescription->id, amount: $request->received_amount, amount_type: 'credit', cash_bank_account_id: $request->account_id);
        }

        $order = $this->salesOrderService->singleSalesOrder(id: $id, with: ['saleProducts']);

        $deletedUnusedSalesOrderProducts = $order->saleProducts()->where('is_delete_in_update', BooleanType::True->value)->get();

        if (count($deletedUnusedSalesOrderProducts) > 0) {

            foreach ($deletedUnusedSalesOrderProducts as $deletedUnusedSalesOrderProduct) {

                $deletedUnusedSalesOrderProduct->delete();
            }
        }

        $this->saleService->adjustSaleInvoiceAmounts(sale: $order);

        $this->salesOrderService->calculateDeliveryLeftQty(order: $order);

        // Add user Log
        $this->userActivityLogService->addLog(action: UserActivityLogActionType::Updated->value, subjectType: UserActivityLogSubjectType::SalesOrder->value, data_obj: $order);

        return null;
    }

    public function searchByOrderIdMethodContainer(string $keyWord): array|object
    {
        $orders = DB::table('sales')
            ->leftJoin('accounts', 'sales.customer_account_id', 'accounts.id')
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('sales.order_id', 'like', "%{$keyWord}%")
            ->where('sales.branch_id', auth()->user()->branch_id)
            ->where('sales.status', SaleStatus::Order->value)
            ->select(
                'sales.id as sales_order_id',
                'sales.order_id',
                'sales.customer_account_id',
                'accounts.name as customer_name',
                'accounts.phone as customer_phone',
                'account_groups.default_balance_type',
            )->limit(35)->get();

        if (count($orders) > 0) {

            return view('search_results_view.sales_order_search_result_list', compact('orders'));
        } else {

            return ['noResult' => 'no result'];
        }
    }

    public function deleteMethodContainer(int $id): ?array
    {
        $deleteSale = $this->saleService->deleteSale($id);

        if (isset($deleteSale['pass']) && $deleteSale['pass'] == false) {

            return ['pass' => false, 'msg' => $deleteSale['msg']];
        }

        $this->userActivityLogService->addLog(action: UserActivityLogActionType::Deleted->value, subjectType: UserActivityLogSubjectType::SalesOrder->value, dataObj: $deleteSale);

        return null;
    }
}
