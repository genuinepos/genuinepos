<?php

namespace App\Http\Controllers\Setups;

use App\Models\Warehouse;
use Illuminate\Http\Request;
use App\Models\WarehouseBranch;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\Setups\BranchService;
use App\Services\Setups\WarehouseService;
use Yajra\DataTables\Facades\DataTables;

class WarehouseController extends Controller
{
    public function __construct(private WarehouseService $warehouseService, private BranchService $branchService)
    {
    }

    public function index(Request $request)
    {
        if (!auth()->user()->can('warehouse')) {

            abort(403, 'Access Forbidden.');
        }

        if ($request->ajax()) {

            return $this->warehouseService->warehouseListTable($request);
        }

        $branches = $this->branchService->branches()->get();

        return view('setups.warehouses.index', compact('branches'));
    }

    public function create()
    {
        if (!auth()->user()->can('warehouse')) {

            abort(403, 'Access Forbidden.');
        }

        return view('setups.warehouses.ajax_view.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'code' => 'required',
            'phone' => 'required',
        ]);

        try {
            DB::beginTransaction();

            $addWarehouse = $this->warehouseService->addWarehouse($request);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }

        return $addWarehouse;
    }

    public function edit($id)
    {
        $w = Warehouse::with(['warehouseBranches'])->where('id', $id)->first();

        $isExistsHeadOffice = DB::table('warehouse_branches')
            ->where('warehouse_id', $id)
            ->where('branch_id', null)
            ->where('is_global', 0)
            ->first();

        $branches = DB::table('branches')->select('id', 'name', 'branch_code')->get();

        return view('settings.warehouses.ajax_view.edit', compact('w', 'branches', 'isExistsHeadOffice'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'code' => 'required',
            'phone' => 'required',
        ]);

        $updateWarehouse = Warehouse::where('id', $id)->first();
        $updateWarehouse->warehouse_name = $request->name;
        $updateWarehouse->warehouse_code = $request->code;
        $updateWarehouse->phone = $request->phone;
        $updateWarehouse->address = $request->address;
        $updateWarehouse->save();

        WarehouseBranch::where('warehouse_id', $id)->delete();

        if (isset($request->branch_ids)) {

            foreach ($request->branch_ids as $branch_id) {

                $__branch_id = $branch_id == 'NULL' ? null : $branch_id;
                $addWarehouseBranch = new WarehouseBranch();
                $addWarehouseBranch->warehouse_id = $updateWarehouse->id;
                $addWarehouseBranch->branch_id = $__branch_id;
                $addWarehouseBranch->save();
            }
        } else {

            $addWarehouseBranch = new WarehouseBranch();
            $addWarehouseBranch->warehouse_id = $updateWarehouse->id;
            $addWarehouseBranch->branch_id = null;
            $addWarehouseBranch->is_global = 1;
            $addWarehouseBranch->save();
        }

        return response()->json('Successfully warehouse is updated');
    }

    public function delete(Request $request, $warehouseId)
    {
        $deleteWarehouse = Warehouse::where('id', $warehouseId)->first();

        if (count($deleteWarehouse->transfer_stock_branch_to_branch) > 0) {
            return response()->json(['errorMsg' => 'Warehouse can\'t be deleted. One or more entry has been created in transfer branch to branch.']);
        }
        if (count($deleteWarehouse->transfer_stock_branch) > 0) {
            return response()->json(['errorMsg' => 'Warehouse can\'t be deleted. One or more entry has been created in transfer warehouse to branch.']);
        }
        if (count($deleteWarehouse->sale_product) > 0) {
            return response()->json(['errorMsg' => 'Warehouse can\'t be deleted. One or more entry has been created in sale.']);
        }
        if (count($deleteWarehouse->transfer_to_branch) > 0) {
            return response()->json(['errorMsg' => 'Warehouse can\'t be deleted. One or more entry has been created in transferred.']);
        }
        if (count($deleteWarehouse->purchase) > 0) {
            return response()->json(['errorMsg' => 'Warehouse can\'t be deleted. One or more entry has been created in purchase.']);
        }
        if (!is_null($deleteWarehouse)) {

            $deleteWarehouse->delete();
        }

        return response()->json('Successfully warehouse is deleted');
    }
}
