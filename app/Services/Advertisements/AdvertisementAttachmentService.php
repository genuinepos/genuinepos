<?php

namespace App\Services\Advertisements;

use App\Utils\FileUploader;
use App\Enums\AdvertisementContentType;
use App\Enums\BooleanType;
use App\Models\Advertisement\AdvertiseAttachment;

class AdvertisementAttachmentService
{
    public function addAdvertisementAttachments(object $request, int $advertisementId): void
    {
        if ($request->content_type == AdvertisementContentType::Image->value) {

            $images = $request->file('images');

            foreach ($images as $index => $image) {

                $data = [
                    'advertisement_id' => $advertisementId,
                    'content_title' => $request->content_titles[$index],
                    'caption' => $request->captions[$index],
                    'image' => FileUploader::fileUpload(fileType: 'advertisementAttachment', uploadableFile: $image),
                ];

                AdvertiseAttachment::create($data);
            }
        } else {

            $data = [
                'advertisement_id' => $advertisementId,
                'video' =>  FileUploader::fileUpload(fileType: 'advertisementAttachment', uploadableFile: $request->file('video')),
            ];

            AdvertiseAttachment::create($data);
        }
    }

    public function updateAdvertisementAttachments(object $request, object $advertisement): void
    {
        if ($advertisement->content_type == AdvertisementContentType::Image->value) {

            foreach ($request->attachment_ids as $index => $attachmentId) {

                if (isset($attachmentId)) {

                    $updateAttachment = $this->singleAdvertisementAttachment(id: $attachmentId);
                    if (isset($updateAttachment)) {

                        $updateAttachment->content_title = $request->content_titles[$index];
                        $updateAttachment->caption = $request->captions[$index];

                        if (isset($request->images[$index])) {

                            $uploadedFile = FileUploader::fileUpload(fileType: 'advertisementAttachment', uploadableFile: $request->images[$index], deletableFile: $updateAttachment->image);

                            $updateAttachment->image = $uploadedFile;
                        }

                        $updateAttachment->is_delete_in_update = BooleanType::False->value;
                        $updateAttachment->save();
                    }
                } else {

                    if (isset($request->images[$index])) {

                        $addAttachment = new AdvertiseAttachment();
                        $addAttachment->advertisement_id = $advertisement->id;
                        $addAttachment->content_title = $request->content_titles[$index];
                        $addAttachment->caption = $request->captions[$index];

                        $addAttachment->image = FileUploader::fileUpload(fileType: 'advertisementAttachment', uploadableFile: $request->images[$index]);
                        $addAttachment->save();
                    }
                }
            }
        } else {

            if ($request->hasFile('video')) {

                $updateAttachment = $this->singleAdvertisementAttachment(id: $request->video_attachment_id);

                if (isset($updateAttachment)) {

                    $uploadedFile = FileUploader::fileUpload(fileType: 'advertisementAttachment', uploadableFile: $request->file('video'), deletableFile: $updateAttachment->video);
                    $updateAttachment->video = $uploadedFile;
                    $updateAttachment->is_delete_in_update = BooleanType::False->value;
                    $updateAttachment->save();
                }
            }
        }

        $this->deleteUnusedAttachments(advertisementId: $advertisement->id);
    }

    private function deleteUnusedAttachments(int $advertisementId): void
    {
        $unusedAttachments = $this->advertisementAttachments()->where('advertisement_id', $advertisementId)->where('is_delete_in_update', BooleanType::True->value)->get();

        foreach ($unusedAttachments as $unusedAttachment) {

            FileUploader::deleteFile(fileType: 'advertisementAttachment', deletableFile: $unusedAttachment->image);

            FileUploader::deleteFile(fileType: 'advertisementAttachment', deletableFile: $unusedAttachment->video);

            $unusedAttachment->delete();
        }
    }

    public function singleAdvertisementAttachment(?int $id, array $with = null): ?object
    {
        $query = AdvertiseAttachment::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query->where('id', $id)->first();
    }

    public function advertisementAttachments(array $with = null): ?object
    {
        $query = AdvertiseAttachment::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query;
    }
}
