<?php

namespace App\Http\Controllers\Sales;

use Illuminate\Http\Request;
use App\Utils\UserActivityLogUtil;
use App\Services\Sales\SaleService;
use App\Http\Controllers\Controller;
use App\Services\Products\UnitService;
use App\Services\Setups\BranchService;
use App\Services\Products\BrandService;
use App\Services\Accounts\AccountService;
use App\Services\Accounts\DayBookService;
use App\Services\Setups\WarehouseService;
use App\Services\Products\CategoryService;
use App\Services\Sales\SaleProductService;
use App\Services\Sales\CashRegisterService;
use App\Services\Products\PriceGroupService;
use App\Services\Setups\BranchSettingService;
use App\Services\Setups\PaymentMethodService;
use App\Services\Products\ProductStockService;
use App\Services\Accounts\AccountFilterService;
use App\Services\Accounts\AccountLedgerService;
use App\Services\Products\ProductLedgerService;
use App\Services\Products\ManagePriceGroupService;
use App\Services\Purchases\PurchaseProductService;
use App\Services\Accounts\AccountingVoucherService;
use App\Services\Accounts\AccountingVoucherDescriptionService;
use App\Services\Accounts\AccountingVoucherDescriptionReferenceService;

class PosSaleController extends Controller
{
    public function __construct(
        private SaleService $saleService,
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
                'bankAccessBranch'
            ])->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
                ->where('branch_id', auth()->user()->branch_id)
                ->whereIn('account_groups.sub_sub_group_number', [2])
                ->select('accounts.id', 'accounts.name', 'accounts.account_number', 'accounts.bank_id', 'accounts.account_group_id')
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
}
