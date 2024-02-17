<?php

namespace App\Http\Controllers\Sales;

use App\Enums\SaleStatus;
use App\Http\Controllers\Controller;
use App\Services\Accounts\AccountService;
use App\Services\Sales\SaleProductService;
use App\Services\Sales\SaleService;
use App\Services\Sales\ShipmentService;
use App\Services\Setups\BranchService;
use App\Utils\UserActivityLogUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ShipmentController extends Controller
{
    public function __construct(
        private ShipmentService $shipmentService,
        private SaleService $saleService,
        private SaleProductService $saleProductService,
        private BranchService $branchService,
        private AccountService $accountService,
        private UserActivityLogUtil $userActivityLogUtil,
    ) {
        $this->middleware('subscriptionRestrictions');
    }

    public function index(Request $request)
    {
        if (!auth()->user()->can('shipment_access')) {

            abort(403, 'Access Forbidden.');
        }

        if ($request->ajax()) {

            return $this->shipmentService->shipmentListTable($request);
        }

        $ownBranchIdOrParentBranchId = auth()->user()?->branch?->parent_branch_id ? auth()->user()?->branch?->parent_branch_id : auth()->user()->branch_id;

        $branches = $this->branchService->branches(with: ['parentBranch'])
            ->orderByRaw('COALESCE(branches.parent_branch_id, branches.id), branches.id')->get();

        $customerAccounts = $this->accountService->customerAndSupplierAccounts($ownBranchIdOrParentBranchId);

        return view('sales.add_sale.shipments.index', compact('branches', 'customerAccounts'));
    }

    public function edit($id)
    {
        $sale = $this->saleService->singleSale(id: $id);

        return view('sales.add_sale.shipments.ajax_views.edit', compact('sale'));
    }

    public function update(Request $request, $id)
    {
        if (!auth()->user()->can('shipment_access')) {

            return response()->json('Access Denied');
        }

        $this->validate($request, [
            'shipment_address' => 'required',
            'shipment_status' => 'required',
        ]);

        try {

            DB::beginTransaction();

            $updateShipmentDetails = $this->shipmentService->updateShipmentDetails(request: $request, id: $id);

            $this->userActivityLogUtil->addLog(action: 1, subject_type: 1, data_obj: $updateShipmentDetails);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json(__('Shipment is updated successfully.'));
    }
}
