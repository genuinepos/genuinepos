<?php

namespace App\Services\TaskManagement;

use App\Utils\CloudFileUploader;
use App\Models\TaskManagement\WorkspaceAttachment;

class WorkspaceAttachmentService
{
    function addWorkspaceAttachments(object $request, int $workspaceId): void
    {
        if ($request->file('attachments')) {

            if (count($request->file('attachments')) > 0) {

                foreach ($request->file('attachments') as $attachment) {

                    $dir = tenant('id') . '/' . 'workspace_attachments';
                    $uploadedFile = CloudFileUploader::uploadFile(path: $dir, uploadableFile: $attachment);

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

            $dir = public_path('uploads/' . tenant('id') . '/' . 'workspace_attachments/');

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
