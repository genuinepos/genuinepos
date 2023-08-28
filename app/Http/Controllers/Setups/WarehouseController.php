<?php

namespace App\Http\Controllers\Setups;

use App\Http\Controllers\Controller;
use App\Services\Setups\BranchService;
use App\Services\Setups\WarehouseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WarehouseController extends Controller
{
    public function __construct(private WarehouseService $warehouseService, private BranchService $branchService)
    {
    }

    public function index(Request $request)
    {
        if (! auth()->user()->can('warehouse')) {

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
        if (! auth()->user()->can('warehouse')) {

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
        $warehouse = $this->warehouseService->singleWarehouse(id: $id);

        return view('setups.warehouses.ajax_view.edit', compact('warehouse'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'code' => 'required',
            'phone' => 'required',
        ]);

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
        try {

            DB::beginTransaction();

            $this->warehouseService->deleteWarehouse($id);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }

        return response()->json(__('Successfully warehouse is deleted'));
    }
}
