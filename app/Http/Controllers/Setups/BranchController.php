<?php

namespace App\Http\Controllers\Setups;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\Setups\BranchStoreRequest;
use App\Http\Requests\Setups\BranchDeleteRequest;
use App\Http\Requests\Setups\BranchUpdateRequest;
use App\Interfaces\Setups\BranchControllerMethodContainersInterface;

class BranchController extends Controller
{
    public function __construct()
    {
        $this->middleware('subscriptionRestrictions');
    }

    public function index(Request $request, BranchControllerMethodContainersInterface $branchControllerMethodContainersInterface)
    {
        $generalSettings = config('generalSettings');

        abort_if(!auth()->user()->can('branches_create') && $generalSettings['subscription']->current_shop_count == 1, 403);

        $indexMethodContainer = $branchControllerMethodContainersInterface->indexMethodContainer(request: $request);

        if ($request->ajax()) {

            return $indexMethodContainer;;
        }

        extract($indexMethodContainer);

        return view('setups.branches.index', compact('currentCreatedBranchCount'));
    }

    public function create(BranchControllerMethodContainersInterface $branchControllerMethodContainersInterface)
    {
        abort_if(!auth()->user()->can('branches_create'), 403);

        $createMethodContainer = $branchControllerMethodContainersInterface->createMethodContainer();

        extract($createMethodContainer);

        return view('setups.branches.ajax_view.create', compact('branches', 'roles', 'currencies', 'timezones', 'branchCode'));
    }

    public function store(BranchStoreRequest $request, BranchControllerMethodContainersInterface $branchControllerMethodContainersInterface)
    {
        try {
            DB::beginTransaction();

            $storeMethodContainer = $branchControllerMethodContainersInterface->storeMethodContainer(request: $request, codeGenerator: $codeGenerator);

            if (isset($storeMethodContainer['pass']) && $storeMethodContainer['pass'] == false) {

                return response()->json(['errorMsg' => $storeMethodContainer['msg']]);
            }

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json(__('Shop created successfully'));
    }

    public function edit($id, BranchControllerMethodContainersInterface $branchControllerMethodContainersInterface)
    {
        abort_if(!auth()->user()->can('branches_edit'), 403);

        $editMethodContainer = $branchControllerMethodContainersInterface->editMethodContainer(id: $id);

        extract($editMethodContainer);

        return view('setups.branches.ajax_view.edit', compact('branches', 'branch', 'currencies', 'timezones', 'branchSettings'));
    }

    public function update($id, BranchUpdateRequest $request, BranchControllerMethodContainersInterface $branchControllerMethodContainersInterface)
    {
        try {
            DB::beginTransaction();

            $branchControllerMethodContainersInterface->updateMethodContainer(id: $id, request: $request);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json(__('Shop updated successfully'));
    }

    public function delete($id, BranchDeleteRequest $request, BranchControllerMethodContainersInterface $branchControllerMethodContainersInterface)
    {
        try {
            DB::beginTransaction();

            $deleteMethodContainer = $branchControllerMethodContainersInterface->deleteMethodContainer(id: $id);

            if (isset($deleteMethodContainer['pass']) && $deleteMethodContainer['pass'] == false) {

                return response()->json(['errorMsg' => $deleteMethodContainer['msg']]);
            }

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json(__('Shop deleted deleted successfully'));
    }

    public function parentWithChildBranches($id, BranchControllerMethodContainersInterface $branchControllerMethodContainersInterface)
    {
        return $branchControllerMethodContainersInterface->deleteMethodContainer(id: $id);
    }

    public function branchCode(BranchControllerMethodContainersInterface $branchControllerMethodContainersInterface, $parentBranchId = null)
    {
        return $branchControllerMethodContainersInterface->branchCodeMethodContainer(parentBranchId: $parentBranchId);
    }
}
