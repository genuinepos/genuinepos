<?php

namespace App\Services\TaskManagement;

use App\Models\TaskManagement\WorkspaceAttachment;
use App\Utils\FileUploader;

class WorkspaceAttachmentService
{
    function addWorkspaceAttachments(object $request, int $workspaceId): void
    {
        if ($request->file('attachments')) {

            if (count($request->file('attachments')) > 0) {

                foreach ($request->file('attachments') as $attachment) {

                    $uploadedFile = FileUploader::fileUpload(fileType: 'workspaceAttachment', uploadableFile: $attachment);

                    WorkspaceAttachment::insert([
                        'workspace_id' => $workspaceId,
                        'attachment' => $uploadedFile,
                        'extension' => $attachment->getClientOriginalExtension(),
                    ]);
                }
            }
        }
    }

    public function deleteWorkspaceAttachment(int $id): void
    {
        $deleteWorkspaceAttachment = $this->singleWorkspaceAttachment(id: $id);

        if (isset($deleteWorkspaceAttachment)) {

            FileUploader::deleteFile(fileType: 'workspaceAttachment', deletableFile: $deleteWorkspaceAttachment->attachment);

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
