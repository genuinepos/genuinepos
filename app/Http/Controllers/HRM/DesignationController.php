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
        $this->middleware('subscriptionRestrictions');
    }

    public function index(Request $request)
    {
        abort_if(!auth()->user()->can('designations_index') || config('generalSettings')['subscription']->features['hrm'] == 0, 403);

        if ($request->ajax()) {

            return $this->designationService->designationsTable();
        }

        return view('hrm.designations.index');
    }


    public function create()
    {
        abort_if(!auth()->user()->can('designations_create') || config('generalSettings')['subscription']->features['hrm'] == 0, 403);

        return view('hrm.designations.ajax_view.create');
    }

    public function store(Request $request)
    {
        abort_if(!auth()->user()->can('designations_create') || config('generalSettings')['subscription']->features['hrm'] == 0, 403);

        $this->designationService->storeValidation(request: $request);
        return $this->designationService->addDesignation(request: $request);
    }

    public function edit($id)
    {
        abort_if(!auth()->user()->can('designations_edit') || config('generalSettings')['subscription']->features['hrm'] == 0, 403);

        $designation = $this->designationService->singleDesignation(id: $id);

        return view('hrm.designations.ajax_view.edit', compact('designation'));
    }

    public function update($id, Request $request)
    {
        abort_if(!auth()->user()->can('designations_edit') || config('generalSettings')['subscription']->features['hrm'] == 0, 403);

        $this->designationService->updateValidation(request: $request, id: $id);
        $this->designationService->updateDesignation(request: $request, id: $id);

        return response()->json(__('Designation updated successfully'));
    }

    public function delete($id)
    {
        abort_if(!auth()->user()->can('designations_delete') || config('generalSettings')['subscription']->features['hrm'] == 0, 403);
    
        $this->designationService->deleteDesignation(id: $id);

        return response()->json(__('Designation deleted successfully'));
    }
}
