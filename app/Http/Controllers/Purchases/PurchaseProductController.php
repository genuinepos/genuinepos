<?php

namespace App\Http\Controllers\Purchases;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Setups\BranchService;
use App\Services\Accounts\AccountService;
use App\Services\Products\CategoryService;
use App\Services\Purchases\PurchaseProductService;

class PurchaseProductController extends Controller
{
    public function __construct(
        private PurchaseProductService $purchaseProductService,
        private BranchService $branchService,
        private AccountService $accountService,
        private CategoryService $categoryService,
    ) {
    }

    public function index(Request $request)
    {
        if (!auth()->user()->can('purchase_all')) {

            abort(403, 'Access Forbidden.');
        }

        if ($request->ajax()) {

            return $this->purchaseProductService->purchaseProductsTable($request);
        }

        $ownBranchIdOrParentBranchId = auth()->user()?->branch?->parent_branch_id ? auth()->user()?->branch?->parent_branch_id : auth()->user()->branch_id;

        $branches = $this->branchService->branches(with: ['parentBranch'])
            ->orderByRaw('COALESCE(branches.parent_branch_id, branches.id), branches.id')->get();

        $supplierAccounts = $this->accountService->customerAndSupplierAccounts($ownBranchIdOrParentBranchId);

        $categories = $this->categoryService->categories()->where('parent_category_id', 'null')->get();

        return view('purchase.purchases.purchase_product_list', compact('branches', 'supplierAccounts', 'categories'));
    }
}
