<?php

namespace App\Http\Controllers\Products;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Setups\BranchService;
use App\Services\Accounts\AccountService;
use App\Services\Products\ExpiredProductService;

class ExpiredProductController extends Controller
{
    public function __construct(
        private ExpiredProductService $expiredProductService,
        private AccountService $accountService,
        private BranchService $branchService,
    ) {
    }

    function index(Request $request)
    {
        if ($request->ajax()) {

            return $this->expiredProductService->expiredProductsTable(request: $request);
        }

        $ownBranchIdOrParentBranchId = auth()->user()?->branch?->parent_branch_id ? auth()->user()?->branch?->parent_branch_id : auth()->user()->branch_id;

        $branches = $this->branchService->branches(with: ['parentBranch'])
            ->orderByRaw('COALESCE(branches.parent_branch_id, branches.id), branches.id')->get();

        $supplierAccounts = $this->accountService->customerAndSupplierAccounts($ownBranchIdOrParentBranchId);

        return view('product.expired_products.index', compact('branches', 'supplierAccounts'));
    }
}
