<?php

namespace App\Http\Controllers\Services;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Setups\BranchService;
use App\Services\Accounts\AccountService;
use App\Http\Requests\Services\ServiceInvoiceIndexRequest;

class ServiceInvoiceController extends Controller
{
    public function __construct(private AccountService $accountService, private BranchService $branchService)
    {
    }

    public function index(ServiceInvoiceIndexRequest $request)
    {
        $ownBranchIdOrParentBranchId = auth()->user()?->branch?->parent_branch_id ? auth()->user()?->branch?->parent_branch_id : auth()->user()->branch_id;

        $branches = $this->branchService->branches(with: ['parentBranch'])
            ->orderByRaw('COALESCE(branches.parent_branch_id, branches.id), branches.id')->get();

        $customerAccounts = $this->accountService->customerAndSupplierAccounts($ownBranchIdOrParentBranchId);

        $ownBranchIdOrParentBranchId = $ownBranchIdOrParentBranchId;

        return view('services.invoices.index', compact('branches', 'customerAccounts'));
    }
}
