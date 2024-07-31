<?php

namespace App\Http\Controllers\HRM;

use App\Http\Controllers\Controller;
use App\Services\Hrm\DesignationService;
use App\Http\Requests\HRM\DesignationEditRequest;
use App\Http\Requests\HRM\DesignationIndexRequest;
use App\Http\Requests\HRM\DesignationStoreRequest;
use App\Http\Requests\HRM\DesignationCreateRequest;
use App\Http\Requests\HRM\DesignationDeleteRequest;
use App\Http\Requests\HRM\DesignationUpdateRequest;

class DesignationController extends Controller
{
    public function __construct(private DesignationService $designationService)
    {
    }

    public function index(DesignationIndexRequest $request)
    {
        if ($request->ajax()) {

            return $this->designationService->designationsTable();
        }

        return view('hrm.designations.index');
    }

    public function create(DesignationCreateRequest $request)
    {
        return view('hrm.designations.ajax_view.create');
    }

    public function store(DesignationStoreRequest $request)
    {
        return $this->designationService->addDesignation(request: $request);
    }

    public function edit($id, DesignationEditRequest $request)
    {
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
