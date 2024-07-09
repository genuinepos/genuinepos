<?php

namespace App\Services\Advertisements;

use App\Enums\BooleanType;
use App\Utils\FileUploader;
use App\Enums\AdvertisementContentType;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Traits\File\FileUploadTrait;
use App\Models\Advertisement\Advertisement;
use App\Models\Advertisement\AdvertiseAttachment;

class AdvertisementService
{
    use FileUploadTrait;
    /**
     * Display a listing of the resource.
     */
    public function advertisementsTable($request)
    {
        $query = Advertisement::with('attachments');

        $this->filter(request: $request, query: $query);

        $advertisements = $query->orderBy('id', 'desc');

        return DataTables::of($advertisements)
            ->addIndexColumn()
            ->addColumn('attachment', function ($row) {

                $html = '';
                foreach ($row->attachments as $attachment) {

                    if ($row->content_type == AdvertisementContentType::Image->value) {

                        $imageUrl = file_link(fileType: 'advertisementAttachment', fileName: $attachment->image);
                        if (FileUploader::isFileExists('advertisementAttachment', $attachment->image)) {

                            $html .= '<img width="60px" height="60px" class="rounded ms-1" src="' . $imageUrl . '" />';
                        } else {

                            $html .= __('Image not found:') . ' ' . $imageUrl . '<br>';
                        }
                    } else {

                        $videoUrl = file_link(fileType: 'advertisementAttachment', fileName: $attachment->video);
                        if (FileUploader::isFileExists('advertisementAttachment', $attachment->video)) {

                            $html .= '<video width="100px" height="70px" controls>';
                            $html .= '<source src="' . $videoUrl . '" type="video/mp4">';
                            $html .= __('Your browser does not support the video tag.');
                            $html .= '</video>';
                        } else {

                            $html .= __('Video not found:') . ' ' . $videoUrl;
                        }
                    }
                }
                return $html;
            })

            ->addColumn('content_type', function ($row) {

                return AdvertisementContentType::tryFrom($row->content_type)->name;
            })
            ->addColumn('status', function ($row) {

                return $row->status == BooleanType::True->value ? __("Active") : __("Inactive");
            })
            ->addColumn('action', function ($row) {

                $editBtn = '<a href="' . route('advertisements.edit', [$row->id]) . '" class="edit-btn btn btn-success btn-sm text-white" title="Edit" data-id="' . $row->id . '"><span class="fas fa-edit"></span></a>';

                $showBtn = '<a target="_blank" href="' . route('advertisements.show', [$row->id]) . '" class="edit-btn btn btn-info btn-sm text-white" title="Edit" data-id="' . $row->id . '"><span class="fas fa-eye"></span></a>';

                return $editBtn . ' ' . $showBtn;
            })

            ->rawColumns(['attachment', 'content_type', 'status', 'action'])
            ->make(true);
    }

    public function addAdvertisement(object $request): object
    {
        $ownBranchIdOrParentBranchId = auth()->user()?->branch?->parent_branch_id ? auth()->user()?->branch?->parent_branch_id : auth()->user()->branch_id;

        return Advertisement::create([
            'branch_id' =>  $ownBranchIdOrParentBranchId,
            'content_type' => $request->content_type,
            'title' => $request->title,
            'status' => $request->status,
        ]);
    }

    public function show(string $id)
    {
        $data = AdvertiseAttachment::where('advertisement_id', $id)->get();
        return $data;
    }

    public function updateAdvertisement(object $request, int $id): object
    {
        $updateAdvertisement = $this->singleAdvertisement(id: $id, with: ['attachments']);
        $updateAdvertisement->title = $request->title;
        $updateAdvertisement->status = $request->status;
        $updateAdvertisement->save();

        foreach ($updateAdvertisement->attachments as $attachment) {
            $attachment->is_delete_in_update = BooleanType::True->value;
            $attachment->save();
        }

        return $updateAdvertisement;

        // Handle image or video update
        // if ($advertisement->content_type == 1) {

        //     if ($request->hasFile('image')) {

        //         foreach ($request->file('image') as $key => $image) {

        //             $imageName = time() . '.' . $image->getClientOriginalExtension();

        //             $image->move($dir, $imageName);

        //             AdvertiseAttachment::create([
        //                 'advertisement_id' => $advertisement->id,
        //                 'content_title' => $request->input('content_title')[$key],
        //                 'caption' => $request->input('caption')[$key],
        //                 'image' => $imageName,
        //             ]);
        //         }
        //     }

        //     if (!empty($request->default_content_title)) {

        //         $this->updateDefaultContent($request);
        //     }
        // } elseif ($advertisement->content_type == 2) {

        //     if ($request->hasFile('video')) {

        //         $advertiseAttachment = AdvertiseAttachment::findOrFail($request->video_id);

        //         $request->validate([
        //             'video' => 'required|mimes:mp4,avi,mov,wmv|max:102400', // 100 MB max
        //         ], [
        //             'video.max' => __('The video must not be larger than 100 MB.'),
        //         ]);

        //         $filePath = $dir . $advertiseAttachment->video;

        //         if (file_exists($filePath)) {

        //             unlink($filePath);
        //         }

        //         AdvertiseAttachment::findOrFail($request->video_id)->delete();
        //         $videoName = time() . '.' . $request->video->getClientOriginalExtension();

        //         $request->video->move($dir, $videoName);

        //         AdvertiseAttachment::create([
        //             'advertisement_id' => $advertisement->id,
        //             'video' => $videoName,
        //         ]);
        //     }
        // }

        return ['status' => 'success', 'message' => __('Advertisement has been updated successfully')];
    }

    public function updateDefaultContent($request)
    {
        foreach ($request->attach_id as $key => $attachId) {

            $contentTitle = $request->default_content_title[$key];
            $caption = $request->default_caption[$key];

            AdvertiseAttachment::where('id', $attachId)->update([
                'content_title' => $contentTitle,
                'caption' => $caption,
            ]);
        }
    }

    public function destroy(string $id)
    {
        $advertiseAttachment = AdvertiseAttachment::findOrFail($id);

        $advertisement = Advertisement::findOrFail($advertiseAttachment->advertisement_id);

        $dir = public_path('uploads/' . tenant('id') . '/' . 'advertisement/');

        if ($advertisement->content_type == 1) {

            $filePath = $dir . $advertiseAttachment->image;
            if (file_exists($filePath)) {

                unlink($filePath);
            }
        } else {

            $filePath = $dir . $advertiseAttachment->video;

            if (file_exists($filePath)) {

                unlink($filePath);
            }
        }

        $advertiseAttachment->delete();

        return ['status' => 'success', 'message' => __('Advertisement has been deleted successfully')];
    }

    public function singleAdvertisement(int $id, array $with = null): ?object
    {
        $query = Advertisement::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query->where('id', $id)->first();
    }

    private function filter(object $request, object $query)
    {
        if ($request->branch_id) {

            if ($request->branch_id == 'NULL') {

                $query->where('branch_id', null);
            } else {

                $query->where('branch_id', $request->branch_id);
            }
        }

        // if (auth()->user()->role_type == 3 || auth()->user()->is_belonging_an_area == 1) {
        if (!auth()->user()->can('has_access_to_all_area') || auth()->user()->is_belonging_an_area == BooleanType::True->value) {

            $query->where('branch_id', auth()->user()->branch_id);
        }

        return $query;
    }
}
