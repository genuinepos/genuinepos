<?php

namespace App\Http\Controllers\HRM;

use Illuminate\Http\Request;
use App\Models\Hrm\Designation;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\Services\Hrm\DesignationService;

class DesignationController extends Controller
{
    public function __construct(
        private DesignationService $designationService,
    ) {
        $this->middleware('expireDate');
    }

    public function index(Request $request)
    {
        if (!auth()->user()->can('designations_index')) {

            abort(403, 'Access Forbidden.');
        }

        if ($request->ajax()) {

            return $this->designationService->designationsTable();
        }

        return view('hrm.designations.index');
    }


    public function create()
    {
        if (!auth()->user()->can('designations_create')) {

            abort(403, 'Access Forbidden.');
        }

        return view('hrm.designations.ajax_view.create');
    }

    public function store(Request $request)
    {
        if (!auth()->user()->can('designations_create')) {

            abort(403, 'Access Forbidden.');
        }

        $this->designationService->storeValidation(request: $request);
        return $this->designationService->addDesignation(request: $request);
    }

    public function edit($id)
    {
        if (!auth()->user()->can('designations_edit')) {

            abort(403, 'Access Forbidden.');
        }

        $designation = $this->designationService->singleDesignation(id: $id);

        return view('hrm.designations.ajax_view.edit', compact('designation'));
    }

    public function update($id, Request $request)
    {
        if (!auth()->user()->can('designations_edit')) {

            abort(403, 'Access Forbidden.');
        }

        $this->designationService->updateValidation(request: $request, id: $id);
        $this->designationService->updateDesignation(request: $request, id: $id);

        return response()->json(__('Designation updated successfully'));
    }

    public function delete($id)
    {
        if (!auth()->user()->can('designations_delete')) {

            abort(403, 'Access Forbidden.');
        }

        $this->designationService->deleteDesignation(id: $id);

        return response()->json(__('Designation deleted successfully'));
    }
}
