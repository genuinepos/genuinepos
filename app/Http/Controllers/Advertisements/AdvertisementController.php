<?php

namespace App\Http\Controllers\Advertisements;

use App\Enums\BooleanType;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\Branches\BranchService;
use App\Enums\AdvertisementContentType;
use App\Services\Advertisements\AdvertisementService;
use App\Http\Requests\Advertisements\AdvertisementEditRequest;
use App\Http\Requests\Advertisements\AdvertisementShowRequest;
use App\Http\Requests\Advertisements\AdvertisementIndexRequest;
use App\Http\Requests\Advertisements\AdvertisementStoreRequest;
use App\Services\Advertisements\AdvertisementAttachmentService;
use App\Http\Requests\Advertisements\AdvertisementCreateRequest;
use App\Http\Requests\Advertisements\AdvertisementDeleteRequest;
use App\Http\Requests\Advertisements\AdvertisementUpdateRequest;

class AdvertisementController extends Controller
{
    public function __construct(
        private AdvertisementService $advertisementService,
        private AdvertisementAttachmentService $advertisementAttachmentService,
        private BranchService $branchService
    ) {
    }

    public function index(AdvertisementIndexRequest $request)
    {
        if ($request->ajax()) {

            return $this->advertisementService->advertisementsTable(request: $request);
        }

        $branches = '';
        if (auth()->user()->can('has_access_to_all_area') && auth()->user()->is_belonging_an_area == BooleanType::False->value) {

            $branches = $this->branchService->branches()->where('parent_branch_id', null)->get();
        }

        return view('advertisements.index', compact('branches'));
    }

    public function create(AdvertisementCreateRequest $request)
    {
        return view('advertisements.create');
    }

    public function store(AdvertisementStoreRequest $request)
    {
        try {
            DB::beginTransaction();

            $addAdvertisement = $this->advertisementService->addAdvertisement(request: $request);

            $this->advertisementAttachmentService->addAdvertisementAttachments(request: $request, advertisementId: $addAdvertisement->id);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json(__('Advertisement has been created successfully'));
    }

    public function show($id, AdvertisementShowRequest $request)
    {
        $advertisement = $this->advertisementService->singleAdvertisement(id: $id, with: ['attachments']);
        if ($advertisement->content_type == AdvertisementContentType::Image->value) {

            return view('advertisements.show_image', compact('advertisement'));
        } else {

            return view('advertisements.show_video', compact('advertisement'));
        }
    }

    public function edit($id, AdvertisementEditRequest $request)
    {
        $advertisement = $this->advertisementService->singleAdvertisement(id: $id, with: ['attachments' => function ($query) {
            $query->orderBy('id', 'asc');
        }]);

        return view('advertisements.edit', compact('advertisement'));
    }

    public function update($id, AdvertisementUpdateRequest $request)
    {
        try {
            DB::beginTransaction();

            $updateAdvertisement = $this->advertisementService->updateAdvertisement(request: $request, id: $id);
            $this->advertisementAttachmentService->updateAdvertisementAttachments(request: $request, advertisement: $updateAdvertisement);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json(__('Advertisement has been updated successfully'));
    }

    public function delete($id, AdvertisementDeleteRequest $request)
    {
        try {
            DB::beginTransaction();

            $this->advertisementService->deleteAdvertisement(id: $id);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json(__('Advertisement has been deleted successfully'));
    }
}
