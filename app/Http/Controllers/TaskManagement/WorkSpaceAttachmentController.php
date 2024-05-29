<?php

namespace App\Http\Controllers\TaskManagement;

use App\Enums\BooleanType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\TaskManagement\WorkspaceAttachmentService;
use App\Http\Requests\TaskManagement\WorkSpaceAttachmentDeleteRequest;

class WorkSpaceAttachmentController extends Controller
{
    public function __construct(
        private WorkspaceAttachmentService $workspaceAttachmentService,
    ) {
        $this->middleware('subscriptionRestrictions');
    }

    public function index($workspaceId)
    {
        abort_if(!auth()->user()->can('workspaces_index') || config('generalSettings')['subscription']->features['task_management'] == BooleanType::True->value, 403);

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
