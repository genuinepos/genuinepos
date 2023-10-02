<?php

namespace App\Http\Controllers\Sales;

use Illuminate\Http\Request;
use App\Utils\UserActivityLogUtil;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\Sales\DraftService;
use App\Services\Setups\BranchService;
use App\Services\Accounts\AccountService;
use App\Services\Sales\SaleProductService;
use App\Services\Sales\DraftProductService;
use App\Services\Products\PriceGroupService;
use App\Services\Accounts\AccountFilterService;
use App\Interfaces\Sales\DraftControllerMethodContainersInterface;

class DraftController extends Controller
{
    public function __construct(
        private DraftService $draftService,
        private DraftProductService $draftProductService,
        private SaleProductService $saleProductService,
        private AccountService $accountService,
        private AccountFilterService $accountFilterService,
        private BranchService $branchService,
        private PriceGroupService $priceGroupService,
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

    public function show($id, DraftControllerMethodContainersInterface $draftControllerMethodContainersInterface)
    {
        if (!auth()->user()->can('sale_draft')) {

            abort(403, 'Access Forbidden.');
        }

        $showMethodContainer = $draftControllerMethodContainersInterface->showMethodContainer(
            id: $id,
            quotationService: $this->draftService,
            saleProductService: $this->saleProductService
        );

        extract($showMethodContainer);

        return view('sales.add_sale.drafts.ajax_views.show', compact('draft', 'customerCopySaleProducts'));
    }

    public function edit($id, DraftControllerMethodContainersInterface $draftControllerMethodContainersInterface){

        $editMethodContainer = $draftControllerMethodContainersInterface->editMethodContainer(
            id: $id,
            draftService: $this->draftService,
            accountService: $this->accountService,
            accountFilterService: $this->accountFilterService,
            priceGroupService: $this->priceGroupService
        );

        extract($editMethodContainer);

        return view('sales.add_sale.drafts.edit', compact('draft', 'customerAccounts', 'accounts', 'saleAccounts', 'taxAccounts', 'priceGroups'));
    }

    function update($id, Request $request, DraftControllerMethodContainersInterface $draftControllerMethodContainersInterface)
    {
        $this->validate($request, [
            'status' => 'required',
            'date' => 'required|date',
        ]);

        try {

            DB::beginTransaction();

            $updateMethodContainer = $draftControllerMethodContainersInterface->updateMethodContainer(
                id: $id,
                request: $request,
                draftService: $this->draftService,
                draftProductService: $this->draftProductService,
            );

            if (isset($updateMethodContainer['pass']) && $updateMethodContainer['pass'] == false) {

                return response()->json(['errorMsg' => $updateMethodContainer['msg']]);
            }

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json(__("Draft updated Successfully."));
    }
}
