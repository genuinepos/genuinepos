<?php

namespace App\Http\Controllers\TaskManagement;

use App\Http\Controllers\Controller;
use App\Services\TaskManagement\WorkspaceAttachmentService;
use App\Http\Requests\TaskManagement\WorkspaceAttachmentIndexRequest;
use App\Http\Requests\TaskManagement\WorkSpaceAttachmentDeleteRequest;

class WorkSpaceAttachmentController extends Controller
{
    public function __construct(private WorkspaceAttachmentService $workspaceAttachmentService)
    {
    }

    public function index($workspaceId, WorkspaceAttachmentIndexRequest $request)
    {
        $attachments = $this->workspaceAttachmentService->workspaceAttachments()
            ->where('workspace_id', $workspaceId)
            ->select(['id', 'attachment', 'extension'])
            ->get();

        return view('task_management.workspaces.attachments.index', compact('attachments'));
    }


    public function delete($id)
    {
        $this->workspaceAttachmentService->deleteWorkspaceAttachment(id: $id);
        return response()->json(__('Document deleted successfully.'));
    }
}
