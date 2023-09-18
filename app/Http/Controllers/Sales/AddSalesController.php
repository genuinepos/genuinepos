<?php

namespace App\Http\Controllers\Sales;

use App\Utils\UserActivityLogUtil;


use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\Setups\BranchService;
use App\Services\Accounts\AccountService;
use App\Services\Accounts\DayBookService;
use App\Services\Setups\WarehouseService;
use App\Services\Setups\BranchSettingService;
use App\Services\Setups\PaymentMethodService;
use App\Services\Products\ProductStockService;
use App\Services\Accounts\AccountFilterService;
use App\Services\Accounts\AccountLedgerService;
use App\Services\Products\ProductLedgerService;
use App\Services\Accounts\AccountingVoucherService;
use App\Services\Accounts\AccountingVoucherDescriptionService;
use App\Services\Accounts\AccountingVoucherDescriptionReferenceService;
use App\Services\Products\PriceGroupService;

class AddSalesController extends Controller
{
    public function __construct(
        private PriceGroupService $priceGroupService,
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
        private UserActivityLogUtil $userActivityLogUtil,
    ) {
    }

    public function create()
    {
        if (!auth()->user()->can('create_add_sale')) {

            abort(403, 'Access Forbidden.');
        }

        $ownBranchIdOrParentBranchId = auth()->user()?->branch?->parent_branch_id ? auth()->user()?->branch?->parent_branch_id : auth()->user()->branch_id;

        $accounts = $this->accountService->accounts(with: [
            'bank:id,name',
            'group:id,sorting_number,sub_sub_group_number',
            'bankAccessBranch'
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
            ->orWhere('is_global', 1)->get(['id', 'warehouse_name', 'warehouse_code', 'is_global']);

        $taxAccounts = $this->accountService->accounts()
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.sub_sub_group_number', 8)
            ->where('accounts.branch_id', auth()->user()->branch_id)
            ->get(['accounts.id', 'accounts.name', 'tax_percent']);

        $customerAccounts = $this->accountService->customerAndSupplierAccounts($ownBranchIdOrParentBranchId);

        $priceGroups = $this->priceGroupService->priceGroups()->get(['id', 'name']);

        return view('sales.add_sale.create', compact(
            'customerAccounts',
            'methods',
            'accounts',
            'saleAccounts',
            'priceGroups',
            'warehouses'
        ));
    }
}
