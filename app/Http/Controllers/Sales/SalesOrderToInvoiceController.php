<?php

namespace App\Http\Controllers\Sales;

use App\Enums\BooleanType;
use Illuminate\Http\Request;
use App\Services\Sales\SaleService;
use App\Http\Controllers\Controller;
use App\Services\Setups\BranchService;
use App\Services\Accounts\AccountService;
use App\Services\Setups\WarehouseService;
use App\Services\Setups\PaymentMethodService;
use App\Services\Accounts\AccountFilterService;
use App\Services\Accounts\AccountBalanceService;

class SalesOrderToInvoiceController extends Controller
{
    public function __construct(
        private SaleService $saleService,
        private PaymentMethodService $paymentMethodService,
        private AccountService $accountService,
        private AccountBalanceService $accountBalanceService,
        private AccountFilterService $accountFilterService,
        private WarehouseService $warehouseService,
        private BranchService $branchService,
    ) {
    }

    public function create($id = null)
    {
        abort_if(!auth()->user()->can('sales_order_to_invoice'), 403);

        $order = null;
        if (isset($id)) {
            $order = $this->saleService->singleSale(id: $id, with: ['customer', 'customer.group', 'saleProducts']);
        }

        $ownBranchIdOrParentBranchId = auth()->user()?->branch?->parent_branch_id ? auth()->user()?->branch?->parent_branch_id : auth()->user()->branch_id;

        $branchName = $this->branchService->branchName();

        $accounts = $this->accountService->accounts(with: [
            'bank:id,name',
            'group:id,sorting_number,sub_sub_group_number',
            'bankAccessBranch',
        ])->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('branch_id', auth()->user()->branch_id)
            ->whereIn('account_groups.sub_sub_group_number', [2])
            ->select('accounts.id', 'accounts.name', 'accounts.account_number', 'accounts.bank_id', 'accounts.account_group_id')
            ->orWhereIn('account_groups.sub_sub_group_number', [1, 11])->get();

        $accounts = $this->accountFilterService->filterCashBankAccounts($accounts);

        $methods = $this->paymentMethodService->paymentMethods(with: ['paymentMethodSetting'])->get();

        $saleAccounts = $this->accountService->accounts()
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.sub_group_number', 15)
            ->where('accounts.branch_id', auth()->user()->branch_id)
            ->get(['accounts.id', 'accounts.name']);

        $warehouses = $this->warehouseService->warehouses()->where('branch_id', auth()->user()->branch_id)
            ->orWhere('is_global', BooleanType::True->value)->get(['id', 'warehouse_name', 'warehouse_code', 'is_global']);

        $taxAccounts = $this->accountService->accounts()
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.sub_sub_group_number', 8)
            ->get(['accounts.id', 'accounts.name', 'tax_percent']);

        $accountBalance = $this->accountBalanceService->accountBalance(accountId: $order?->customer_account_id);

        return view('sales.order_to_invoice.create', compact('ownBranchIdOrParentBranchId', 'branchName', 'accounts', 'methods', 'saleAccounts', 'warehouses', 'taxAccounts', 'order', 'accountBalance'));
    }
}
