<?php

namespace App\Services\Advertisements;

use App\Enums\BooleanType;
use App\Utils\FileUploader;
use App\Enums\AdvertisementContentType;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Advertisement\Advertisement;

class AdvertisementService
{
    public function advertisementsTable($request)
    {
        $generalSettings = config('generalSettings');
        $query = Advertisement::with('branch:id,name', 'attachments');

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

            ->editColumn('branch', function ($row) use ($generalSettings) {

                if ($row->branch) {

                    return $row->branch->name;
                } else {

                    return $generalSettings['business_or_shop__business_name'];
                }
            })

            ->addColumn('content_type', function ($row) {

                return AdvertisementContentType::tryFrom($row->content_type)->name;
            })
            ->addColumn('status', function ($row) {

                return $row->status == BooleanType::True->value ? __("Active") : __("Inactive");
            })
            ->addColumn('action', function ($row) {

                $btn = '<a target="_blank" href="' . route('advertisements.show', [$row->id]) . '" class="edit-btn btn btn-info btn-sm text-white" title="Edit"><span class="fas fa-eye"></span></a>';

                if (auth()->user()->can('advertisements_edit')) {

                    $btn .= '<a href="' . route('advertisements.edit', [$row->id]) . '" class="edit-btn btn btn-success btn-sm text-white ms-1" title="Edit"><span class="fas fa-edit"></span></a>';
                }

                if (auth()->user()->can('advertisements_delete')) {

                    $btn .= '<a href="' . route('advertisements.delete', [$row->id]) . '" class="edit-btn btn btn-danger btn-sm text-white ms-1" title="Delete" id="delete"><span class="fas fa-trash "></span></a>';
                }

                return $btn;
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
    }

    public function deleteAdvertisement(int $id): void
    {
        $deleteAdvertisement = $this->singleAdvertisement(id: $id, with: ['attachments']);

        if (isset($deleteAdvertisement)) {

            foreach ($deleteAdvertisement->attachments as $attachment) {

                FileUploader::deleteFile(fileType: 'advertisementAttachment', deletableFile: $attachment->image);

                FileUploader::deleteFile(fileType: 'advertisementAttachment', deletableFile: $attachment->video);
            }

            $deleteAdvertisement->delete();
        }
    }

    public function singleAdvertisement(int $id, array $with = null): ?object
    {
        $query = Advertisement::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query->where('id', $id)->firstOrFail();
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
