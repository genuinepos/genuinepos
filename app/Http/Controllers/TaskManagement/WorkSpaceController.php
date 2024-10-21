<?php

namespace App\Http\Controllers\TaskManagement;

use Illuminate\Support\Facades\DB;
use App\Services\Users\UserService;
use App\Http\Controllers\Controller;
use App\Services\Branches\BranchService;
use App\Services\CodeGenerationService;
use App\Services\TaskManagement\WorkspaceService;
use App\Services\TaskManagement\WorkspaceUserService;
use App\Http\Requests\TaskManagement\WorkspaceEditRequest;
use App\Http\Requests\TaskManagement\WorkspaceIndexRequest;
use App\Http\Requests\TaskManagement\WorkspaceStoreRequest;
use App\Services\TaskManagement\WorkspaceAttachmentService;
use App\Http\Requests\TaskManagement\WorkspaceCreateRequest;
use App\Http\Requests\TaskManagement\WorkSpaceDeleteRequest;
use App\Http\Requests\TaskManagement\WorkspaceUpdateRequest;

class WorkSpaceController extends Controller
{
    public function __construct(
        private WorkspaceService $workspaceService,
        private WorkspaceUserService $workspaceUserService,
        private WorkspaceAttachmentService $workspaceAttachmentService,
        private BranchService $branchService,
        private UserService $userService,
    ) {}

    public function index(WorkspaceIndexRequest $request)
    {
        if ($request->ajax()) {

            return $this->workspaceService->workspacesTable(request: $request);
        }

        $branches = $this->branchService->branches(with: ['parentBranch'])
            ->orderByRaw('COALESCE(branches.parent_branch_id, branches.id), branches.id')->get();

        return view('task_management.workspaces.index', compact('branches'));
    }

    public function create(WorkspaceCreateRequest $request)
    {
        $users = $this->userService->users()->where('branch_id', auth()->user()->branch_id)
            ->select(['id', 'prefix', 'name', 'last_name'])->get();

        return view('task_management.workspaces.ajax_view.create', compact('users'));
    }

    public function store(WorkspaceStoreRequest $request, CodeGenerationService $codeGenerator)
    {
        try {
            DB::beginTransaction();

            $branch = $this->branchService->singleBranch(id: auth()->user()->branch_id, with: ['parentBranch', 'childBranches']);
            $addWorkspace = $this->workspaceService->addWorkspace(request: $request, branch: $branch, codeGenerator: $codeGenerator);
            $this->workspaceUserService->addWorkspaceUsers(request: $request, workspaceId: $addWorkspace->id);
            $this->workspaceAttachmentService->addWorkspaceAttachments(request: $request, workspaceId: $addWorkspace->id);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json(__('Project created successfully.'));
    }

    public function edit($id, WorkspaceEditRequest $request)
    {
        $workspace = $this->workspaceService->singleWorkspace(id: $id, with: ['users']);
        abort_if(!$workspace, 404);
        $users = $this->userService->users()->where('branch_id', auth()->user()->branch_id)->get(['id', 'prefix', 'name', 'last_name']);

        return view('task_management.workspaces.ajax_view.edit', compact('workspace', 'users'));
    }

    public function update(WorkspaceUpdateRequest $request, $id)
    {
        try {
            DB::beginTransaction();

            $updateWorkspace = $this->workspaceService->updateWorkspace(request: $request, id: $id);
            $this->workspaceUserService->updateWorkspaceUsers(request: $request, workspaceId: $updateWorkspace->id);
            $this->workspaceAttachmentService->addWorkspaceAttachments(request: $request, workspaceId: $updateWorkspace->id);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json(__('Project updated successfully.'));
    }

    public function delete(WorkSpaceDeleteRequest $request, $id)
    {
        try {
            DB::beginTransaction();

            $this->workspaceService->deleteWorkspace(id: $id);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json(__('Project deleted successfully.'));
    }
}
