<?php

namespace App\Services\TaskManagement;

use Carbon\Carbon;
use App\Enums\BooleanType;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use App\Models\TaskManagement\WorkspaceAttachment;

class WorkspaceAttachmentService
{
    function addWorkspaceAttachments(object $request, int $workspaceId): void
    {
        if ($request->file('attachments')) {

            if (count($request->file('attachments')) > 0) {

                $dir = public_path('uploads/workspace_attachments');

                if (!\File::isDirectory($dir)) {

                    \File::makeDirectory($dir, 493, true);
                }

                foreach ($request->file('attachments') as $attachment) {

                    $wsAttachment = $attachment;
                    $wsAttachmentName = uniqid() . '.' . $wsAttachment->getClientOriginalExtension();
                    $wsAttachment->move($dir, $wsAttachmentName);

                    WorkspaceAttachment::insert([
                        'workspace_id' => $workspaceId,
                        'attachment' => $wsAttachmentName,
                        'extension' => $wsAttachment->getClientOriginalExtension(),
                    ]);
                }
            }
        }
    }

    public function deleteWorkspaceAttachment(int $id): void
    {

        $deleteWorkspaceAttachment = $this->singleWorkspaceAttachment(id: $id);

        if (isset($deleteWorkspaceAttachment)) {

            $dir = public_path('uploads/workspace_attachments/');

            if (file_exists($dir . $deleteWorkspaceAttachment->attachment)) {

                unlink($dir . $deleteWorkspaceAttachment->attachment);
            }

            $deleteWorkspaceAttachment->delete();
        }
    }

    public function singleWorkspaceAttachment(int $id, array $with = null)
    {
        $query = WorkspaceAttachment::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query->where('id', $id)->first();
    }

    public function workspaceAttachments(array $with = null)
    {
        $query = WorkspaceAttachment::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query;
    }
}
