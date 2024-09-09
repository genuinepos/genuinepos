<?php

namespace App\Http\Controllers\Branches;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\Branches\BranchEditRequest;
use App\Http\Requests\Branches\BranchIndexRequest;
use App\Http\Requests\Branches\BranchStoreRequest;
use App\Http\Requests\Branches\BranchCreateRequest;
use App\Http\Requests\Branches\BranchDeleteRequest;
use App\Http\Requests\Branches\BranchUpdateRequest;
use App\Interfaces\Branches\BranchControllerMethodContainersInterface;

class BranchController extends Controller
{
    public function index(BranchIndexRequest $request, BranchControllerMethodContainersInterface $branchControllerMethodContainersInterface)
    {
        $generalSettings = config('generalSettings');
        
        $indexMethodContainer = $branchControllerMethodContainersInterface->indexMethodContainer(request: $request);

        if ($request->ajax()) {

            return $indexMethodContainer;
        }

        extract($indexMethodContainer);

        return view('branches.index', compact('currentCreatedBranchCount'));
    }

    public function create(BranchCreateRequest $request, BranchControllerMethodContainersInterface $branchControllerMethodContainersInterface)
    {
        $createMethodContainer = $branchControllerMethodContainersInterface->createMethodContainer();

        extract($createMethodContainer);

        return view('branches.ajax_view.create', compact('branches', 'roles', 'currencies', 'timezones', 'branchCode'));
    }

    public function store(BranchStoreRequest $request, BranchControllerMethodContainersInterface $branchControllerMethodContainersInterface)
    {
        try {
            DB::beginTransaction();

            $storeMethodContainer = $branchControllerMethodContainersInterface->storeMethodContainer(request: $request);

            if (isset($storeMethodContainer['pass']) && $storeMethodContainer['pass'] == false) {

                return response()->json(['errorMsg' => $storeMethodContainer['msg']]);
            }

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json(__('Store created successfully'));
    }

    public function edit($id, BranchEditRequest $request, BranchControllerMethodContainersInterface $branchControllerMethodContainersInterface)
    {
        $editMethodContainer = $branchControllerMethodContainersInterface->editMethodContainer(id: $id);

        extract($editMethodContainer);

        return view('branches.ajax_view.edit', compact('branches', 'branch', 'currencies', 'timezones', 'branchSettings'));
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

        return response()->json(__('Store updated successfully'));
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

        return response()->json(__('Store deleted successfully'));
    }

    public function deleteLogo($id, BranchControllerMethodContainersInterface $branchControllerMethodContainersInterface)
    {
        $deleteLogoMethodContainer = $branchControllerMethodContainersInterface->deleteLogoMethodContainer(id: $id);

        return response()->json(__('Store logo is deleted successfully'));
    }

    public function parentWithChildBranches($id, BranchControllerMethodContainersInterface $branchControllerMethodContainersInterface)
    {
        return $branchControllerMethodContainersInterface->parentWithChildBranchesMethodContainer(id: $id);
    }

    public function branchCode(BranchControllerMethodContainersInterface $branchControllerMethodContainersInterface, $parentBranchId = null)
    {
        return $branchControllerMethodContainersInterface->branchCodeMethodContainer(parentBranchId: $parentBranchId);
    }
}
