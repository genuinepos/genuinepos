<?php

namespace App\Http\Controllers\Sales;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Setups\BranchService;
use App\Services\Accounts\AccountService;
use App\Services\Sales\SaleProductService;
use App\Services\Accounts\AccountFilterService;

class SoldProductController extends Controller
{
    public function __construct(
        private SaleProductService $saleProductService,
        private AccountService $accountService,
        private AccountFilterService $accountFilterService,
        private BranchService $branchService,
    ) {
    }

    public function index(Request $request)
    {
        if (!auth()->user()->can('view_add_sale')) {

            abort(403, 'Access Forbidden.');
        }

        if ($request->ajax()) {

            return $this->saleProductService->soldProductListTable($request);
        }

        $ownBranchIdOrParentBranchId = auth()->user()?->branch?->parent_branch_id ? auth()->user()?->branch?->parent_branch_id : auth()->user()->branch_id;

        $branches = $this->branchService->branches(with: ['parentBranch'])
            ->orderByRaw('COALESCE(branches.parent_branch_id, branches.id), branches.id')->get();

        $customerAccounts = $this->accountService->customerAndSupplierAccounts($ownBranchIdOrParentBranchId);

        return view('sales.add_sale.sold_products.index', compact('branches', 'customerAccounts'));
    }
}
