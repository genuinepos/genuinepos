<?php

namespace App\Http\Controllers\HRM;

use App\Enums\BooleanType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Hrm\DesignationService;
use App\Http\Requests\HRM\DesignationStoreRequest;
use App\Http\Requests\HRM\DesignationDeleteRequest;
use App\Http\Requests\HRM\DesignationUpdateRequest;

class DesignationController extends Controller
{
    public function __construct(private DesignationService $designationService)
    {
    }

    public function index(Request $request)
    {
        abort_if(!auth()->user()->can('designations_index'), 403);

        if ($request->ajax()) {

            return $this->designationService->designationsTable();
        }

        return view('hrm.designations.index');
    }

    public function create()
    {
        abort_if(!auth()->user()->can('designations_create'), 403);

        return view('hrm.designations.ajax_view.create');
    }

    public function store(DesignationStoreRequest $request)
    {
        return $this->designationService->addDesignation(request: $request);
    }

    public function edit($id)
    {
        abort_if(!auth()->user()->can('designations_edit'), 403);

        $designation = $this->designationService->singleDesignation(id: $id);

        return view('hrm.designations.ajax_view.edit', compact('designation'));
    }

    public function update($id, DesignationUpdateRequest $request)
    {
        $this->designationService->updateDesignation(request: $request, id: $id);
        return response()->json(__('Designation updated successfully'));
    }

    public function delete($id, DesignationDeleteRequest $request)
    {
        $this->designationService->deleteDesignation(id: $id);
        return response()->json(__('Designation deleted successfully'));
    }
}
