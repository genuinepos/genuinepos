<?php

namespace App\Http\Controllers\Sales;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\Sales\SaleService;
use App\Http\Controllers\Controller;
use App\Services\Branches\BranchService;
use App\Services\Sales\ShipmentService;
use App\Enums\UserActivityLogActionType;
use App\Enums\UserActivityLogSubjectType;
use App\Services\Accounts\AccountService;
use App\Services\Users\UserActivityLogService;
use App\Http\Requests\Sales\ShipmentEditRequest;
use App\Http\Requests\Sales\ShipmentIndexRequest;
use App\Http\Requests\Sales\ShipmentUpdateRequest;

class ShipmentController extends Controller
{
    public function __construct(
        private ShipmentService $shipmentService,
        private SaleService $saleService,
        private BranchService $branchService,
        private AccountService $accountService,
        private UserActivityLogService $userActivityLogService,
    ) {}

    public function index(ShipmentIndexRequest $request)
    {
        if ($request->ajax()) {

            return $this->shipmentService->shipmentListTable($request);
        }

        $ownBranchIdOrParentBranchId = auth()->user()?->branch?->parent_branch_id ? auth()->user()?->branch?->parent_branch_id : auth()->user()->branch_id;

        $branches = $this->branchService->branches(with: ['parentBranch'])
            ->orderByRaw('COALESCE(branches.parent_branch_id, branches.id), branches.id')->get();

        $customerAccounts = $this->accountService->customerAndSupplierAccounts($ownBranchIdOrParentBranchId);

        return view('sales.add_sale.shipments.index', compact('branches', 'customerAccounts'));
    }

    public function edit($id, ShipmentEditRequest $request)
    {
        $sale = $this->saleService->singleSale(id: $id);

        return view('sales.add_sale.shipments.ajax_views.edit', compact('sale'));
    }

    public function update(ShipmentUpdateRequest $request, $id)
    {
        try {
            DB::beginTransaction();

            $updateShipmentDetails = $this->shipmentService->updateShipmentDetails(request: $request, id: $id);

            $this->userActivityLogService->addLog(action: UserActivityLogActionType::Updated->value, subjectType: UserActivityLogSubjectType::UpdateShipmentDetails->value, dataObj: $updateShipmentDetails);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json(__('Shipment is updated successfully.'));
    }
}
