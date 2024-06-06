<?php

namespace App\Http\Controllers\Services;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Services\StatusService;
use App\Http\Requests\Services\StatusStoreRequest;
use App\Http\Requests\Services\StatusDeleteRequest;
use App\Http\Requests\Services\StatusUpdateRequest;

class StatusController extends Controller
{
    public function __construct(private StatusService $statusService)
    {
    }

    public function statusTable(Request $request)
    {
        if ($request->ajax()) {

            return $this->statusService->statusTable();
        }
    }

    public function create()
    {
        return view('services.settings.ajax_views.status.create');
    }

    public function store(StatusStoreRequest $request)
    {
        return $this->statusService->addStatus(request: $request);
    }

    public function edit($id)
    {
        $status = $this->statusService->singleStatus(id: $id);
        return view('services.settings.ajax_views.status.edit', compact('status'));
    }

    public function update($id, StatusUpdateRequest $request)
    {
        $this->statusService->updateStatus(id: $id, request: $request);

        return response()->json(__('Status updated successfully'));
    }

    public function delete($id, StatusDeleteRequest $request)
    {
        $this->statusService->deleteStatus(id: $id);

        return response()->json(__('Status deleted successfully'));
    }
}
