<?php

namespace App\Http\Controllers\TaskManagement;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\Users\UserService;
use App\Http\Controllers\Controller;
use App\Services\Setups\BranchService;
use App\Services\CodeGenerationService;
use App\Services\TaskManagement\WorkspaceService;
use App\Services\TaskManagement\WorkspaceUserService;
use App\Http\Requests\TaskManagement\WorkspaceStoreRequest;
use App\Http\Requests\TaskManagement\WorkspaceUpdateRequest;
use App\Services\TaskManagement\WorkspaceAttachmentService;

class WorkSpaceController extends Controller
{
    public function __construct(
        private WorkspaceService $workspaceService,
        private WorkspaceUserService $workspaceUserService,
        private WorkspaceAttachmentService $workspaceAttachmentService,
        private BranchService $branchService,
        private UserService $userService,
    ) {
        $this->middleware('subscriptionRestrictions');
    }

    public function index(Request $request)
    {
        abort_if(!auth()->user()->can('workspaces_index') || config('generalSettings')['subscription']->features['task_management'] == 0, 403);

        if ($request->ajax()) {

            return $this->workspaceService->workspacesTable(request: $request);
        }

        $branches = $this->branchService->branches(with: ['parentBranch'])
            ->orderByRaw('COALESCE(branches.parent_branch_id, branches.id), branches.id')->get();

        return view('task_management.workspaces.index', compact('branches'));
    }

    public function create()
    {
        abort_if(!auth()->user()->can('workspaces_create') || config('generalSettings')['subscription']->features['task_management'] == 0, 403);

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

    public function edit($id)
    {
        abort_if(!auth()->user()->can('workspaces_edit') || config('generalSettings')['subscription']->features['task_management'] == 0, 403);

        $workspace = $this->workspaceService->singleWorkspace(id: $id, with: ['users']);
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

    public function delete(Request $request, $id)
    {
        abort_if(!auth()->user()->can('workspaces_delete') || config('generalSettings')['subscription']->features['task_management'] == 0, 403);

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
