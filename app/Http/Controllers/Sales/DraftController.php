<?php

namespace App\Http\Controllers\Sales;

use Illuminate\Http\Request;
use App\Utils\UserActivityLogUtil;
use App\Http\Controllers\Controller;
use App\Services\Sales\DraftService;
use App\Services\Setups\BranchService;
use App\Services\Accounts\AccountService;
use App\Services\Sales\SaleProductService;

class DraftController extends Controller
{
    public function __construct(
        private DraftService $draftService,
        private SaleProductService $saleProductService,
        private AccountService $accountService,
        private BranchService $branchService,
        private UserActivityLogUtil $userActivityLogUtil,
    ) {
    }

    public function index(Request $request)
    {
        if (!auth()->user()->can('sale_draft')) {

            abort(403, 'Access Forbidden.');
        }

        if ($request->ajax()) {

            return $this->draftService->draftListTable($request);
        }

        $ownBranchIdOrParentBranchId = auth()->user()?->branch?->parent_branch_id ? auth()->user()?->branch?->parent_branch_id : auth()->user()->branch_id;

        $branches = $this->branchService->branches(with: ['parentBranch'])
            ->orderByRaw('COALESCE(branches.parent_branch_id, branches.id), branches.id')->get();

        $customerAccounts = $this->accountService->customerAndSupplierAccounts($ownBranchIdOrParentBranchId);

        return view('sales.add_sale.drafts.index', compact('branches', 'customerAccounts'));
    }

    public function show($id)
    {
        if (!auth()->user()->can('sale_draft')) {

            abort(403, 'Access Forbidden.');
        }

        $draft = $this->draftService->singleDraft(id: $id, with: [
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
        ]);

        $customerCopySaleProducts = $this->saleProductService->customerCopySaleProducts(saleId: $draft->id);

        return view('sales.add_sale.drafts.ajax_views.show', compact('draft', 'customerCopySaleProducts'));
    }
}
