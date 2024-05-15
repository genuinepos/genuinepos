<?php

namespace App\Http\Controllers\TaskManagement;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\TaskManagement\WorkspaceAttachmentService;

class WorkSpaceAttachmentController extends Controller
{
    public function __construct(
        private WorkspaceAttachmentService $workspaceAttachmentService,
    ) {
    }

    public function index($workspaceId)
    {
        abort_if(!auth()->user()->can('workspaces_index'), 403);

        $attachments = $this->workspaceAttachmentService->workspaceAttachments()
            ->where('workspace_id', $workspaceId)
            ->select(['id', 'attachment', 'extension'])
            ->get();

        return view('task_management.workspaces.attachments.index', compact('attachments'));
    }


    public function delete($id)
    {
        abort_if(!auth()->user()->can('workspaces_index'), 403);

        $this->workspaceAttachmentService->deleteWorkspaceAttachment(id: $id);

        return response()->json(__('Document deleted successfully.'));
    }
}
