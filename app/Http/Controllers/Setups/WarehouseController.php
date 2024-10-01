<?php

namespace App\Http\Controllers\Setups;

use App\Enums\BooleanType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\Branches\BranchService;
use App\Services\Setups\WarehouseService;

class WarehouseController extends Controller
{
    public function __construct(private WarehouseService $warehouseService, private BranchService $branchService) {}

    public function index(Request $request)
    {
        abort_if(!auth()->user()->can('warehouses_index'), 403);

        if ($request->ajax()) {

            return $this->warehouseService->warehouseListTable($request);
        }

        $count = $this->warehouseService->warehouses()->count();

        $branches = $this->branchService->branches(with: ['parentBranch'])
            ->orderByRaw('COALESCE(branches.parent_branch_id, branches.id), branches.id')->get();

        return view('setups.warehouses.index', compact('branches', 'count'));
    }

    public function create()
    {
        abort_if(!auth()->user()->can('warehouses_add'), 403);
        
        return view('setups.warehouses.ajax_view.create');
    }

    public function store(Request $request)
    {
        abort_if(!auth()->user()->can('warehouses_add'), 403);

        $this->warehouseService->warehouseValidation(request: $request);

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
        abort_if(!auth()->user()->can('warehouses_edit'), 403);

        $warehouse = $this->warehouseService->singleWarehouse(id: $id);
        return view('setups.warehouses.ajax_view.edit', compact('warehouse'));
    }

    public function update(Request $request, $id)
    {
        abort_if(!auth()->user()->can('warehouses_edit'), 403);

        $this->warehouseService->warehouseValidation(request: $request);

        try {
            DB::beginTransaction();

            $this->warehouseService->updateWarehouse(id: $id, request: $request);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }

        return response()->json(__('Warehouse is updated successfully'));
    }

    public function delete(Request $request, $id)
    {
        abort_if(!auth()->user()->can('warehouses_delete'), 403);

        try {
            DB::beginTransaction();

            $deleteWarehouse = $this->warehouseService->deleteWarehouse($id);

            if (isset($deleteWarehouse['pass']) && $deleteWarehouse['pass'] == false) {

                return response()->json(['errorMsg' => $deleteWarehouse['msg']]);
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }

        return response()->json(__('Successfully warehouse is deleted'));
    }

    public function warehousesByBranch($branchId, $isAllowGlobalWarehouse = 0)
    {
        $__branchId = $branchId == 'NULL' ? null : $branchId;

        $query = $this->warehouseService->Warehouses()->where('branch_id', $__branchId)->where('is_global', BooleanType::False->value);

        if ($isAllowGlobalWarehouse == BooleanType::True->value) {

            $query->orWhere('is_global', BooleanType::True->value);
        }

        $warehouses = $query->get();

        return $warehouses;
    }
}
