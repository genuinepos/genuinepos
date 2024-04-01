<?php

namespace App\Services\Advertisement;

use App\Models\Advertisement\Advertisements;
use App\Models\Advertisement\AdvertiseAttachment;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;
use App\Http\Traits\File\FileUploadTrait;

class AdvertisementService
{

    use FileUploadTrait;
    /**
     * Display a listing of the resource.
     */
    public function index($request)
    {
        $data = Advertisements::with('attachments')
            ->orderBy('id', 'desc')->get();

        if ($request->ajax()) {
            return DataTables::of($data)
                ->addIndexColumn()

                ->addColumn('attachment', function ($row) {
                    $attachments = $row->attachments;
                    $html = '';
                    foreach ($attachments as $attachment) {
                        if ($row->content_type == 1) {
                            // Display image
                            $imageUrl = asset('uploads/advertisement/' . tenant('id') . '/file' . '/' . $attachment->image);
                            if (file_exists(public_path('uploads/advertisement/' . tenant('id') . '/file' . '/' . $attachment->image))) {
                                $html .= '<img width="100px" height="100px" src="' . $imageUrl . '" />';
                            } else {
                                $html .= 'Image not found: ' . $imageUrl;
                            }
                        } else {
                            // Display video
                            $videoUrl = asset('uploads/advertisement/' . tenant('id') . '/file' . '/' . $attachment->video);
                            if (file_exists(public_path('uploads/advertisement/' . tenant('id') . '/file' . '/' . $attachment->video))) {
                                $html .= '<video width="100px" height="100px" controls>';
                                $html .= '<source src="' . $videoUrl . '" type="video/mp4">';
                                $html .= 'Your browser does not support the video tag.';
                                $html .= '</video>';
                            } else {
                                $html .= 'Video not found: ' . $videoUrl;
                            }
                        }
                    }
                    return $html;
                })


                ->addColumn('content_type', function ($row) {
                    return $row->content_type == 1 ? "Image" : "Video";
                })
                ->addColumn('status', function ($row) {
                    return $row->status == 1 ? "Active" : "Inactive";
                })
                ->addColumn('action', function ($row) {
                    $editBtn = '<a href="' . route('advertise.edit', ['advertise' => $row->id]) . '" class="edit-btn btn btn-success btn-sm text-white" title="Edit" data-id="' . $row->id . '">
                                <span class="fas fa-edit"></span>
                            </a>';
                    $showBtn = '<a target="_blank" href="' . route('advertise.show', ['advertise' => $row->id]) . '" class="edit-btn btn btn-info btn-sm text-white" title="Edit" data-id="' . $row->id . '">
                                <span class="fas fa-eye"></span>
                            </a>';
                    return $editBtn . ' ' . $showBtn;
                })

                ->rawColumns(['attachment', 'content_type', 'status', 'action'])
                ->make(true);
        }

        return view('advertisement.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('advertisement.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store($request)
    {
        $request->validate([
            'title' => 'required',
            'content_type' => 'required',
            'logo' => 'mimes:jpeg,jpg,png,gif,avif,webp|max:1024', //1 mb max
        ]);

        if ($request->content_type == 1) {
            $request->validate([
                'image' => 'required',
                'image.*' => 'required|mimes:jpeg,jpg,png,gif,avif,webp|max:1024', //1 mb max
            ]);
        } elseif ($request->content_type == 2) {
            $request->validate([
                'video' => 'required|mimes:mp4,avi,mov,wmv|max:102400', // 100MB max 102400 KB
            ], [
                'video.max' => 'The video must not be larger than 100 MB.',
            ]);
        }

        $advertisement = Advertisements::create([
            'content_type' => $request->content_type,
            'title' => $request->title,
            'status' => $request->status,
        ]);

        if ($request->hasFile('image')) {
            $imageNames = $this->multiple($request->file('image'), '/uploads/advertisement/' . tenant('id') . '/file');
            foreach ($imageNames as $key => $imageName) {
                $data = [
                    'advertisement_id' => $advertisement->id,
                    'content_title' => $request->content_title[$key],
                    'caption' => $request->caption[$key],
                    'image' => $imageName,
                ];
                AdvertiseAttachment::create($data);
            }
        } else {
            $imageName = $this->single($request, '/uploads/advertisement/' . tenant('id') . '/file', 'video');
            $data = [
                'advertisement_id' => $advertisement->id,
                'video' =>  $imageName,
            ];
            AdvertiseAttachment::create($data);
        }

        if ($advertisement) {
            return ['status' => 'success', 'message' => 'Advertisement has been created successfully'];
        } else {
            return ['status' => 'error', 'message' => 'Failed to create Advertisement'];
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = AdvertiseAttachment::where('advertisement_id', $id)->get();
        return $data;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = Advertisements::with(['attachments' => function ($query) {
            $query->orderByDesc('id');
        }])->findOrFail($id);
        return $data;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {

        try {
            $validatedData = $request->validate([
                'title' => 'required',
                'logo' => 'nullable|mimes:jpeg,jpg,png,gif,avif,webp|max:1024',
                'status' => 'required',
            ]);

            $advertisement = Advertisements::findOrFail($id);

            $advertisement->update([
                'title' => $validatedData['title'],
                'status' => $validatedData['status'],
            ]);

            // Handle image or video update
            if
            ($advertisement->content_type == 1) {
                if ($request->hasFile('image')) {
                    foreach ($request->file('image') as $key => $image) {
                        $imageName = time() . '.' . $image->getClientOriginalExtension();
                        $image->move(public_path('uploads/advertisement/' . tenant('id') . '/file'), $imageName);
                        AdvertiseAttachment::create([
                            'advertisement_id' => $advertisement->id,
                            'content_title' => $request->input('content_title')[$key],
                            'caption' => $request->input('caption')[$key],
                            'image' => $imageName,
                        ]);
                    }
                }
                if (!empty($request->default_content_title)) {
                    $this->updateDefaultContent($request);
                }

            } elseif ($advertisement->content_type == 2) {
            
                if ($request->hasFile('video')) {
                    $advertiseAttachment = AdvertiseAttachment::findOrFail($request->video_id);
                    $request->validate([
                        'video' => 'required|mimes:mp4,avi,mov,wmv|max:102400', // 100 MB max
                    ], [
                        'video.max' => 'The video must not be larger than 100 MB.',
                    ]);
                    $filePath = public_path('uploads/advertisement/' . tenant('id') . '/' . 'file/' . $advertiseAttachment->video);
                    if (file_exists($filePath)) {
                        unlink($filePath);
                    }
                    AdvertiseAttachment::findOrFail($request->video_id)->delete();
                    $videoName = time() . '.' . $request->video->getClientOriginalExtension();
                    $request->video->move(public_path('uploads/advertisement/' . tenant('id') . '/file'), $videoName);
                    AdvertiseAttachment::create([
                        'advertisement_id' => $advertisement->id,
                        'video' => $videoName,
                    ]);
                }

            }
        } catch (\Exception $e) {
            return ['status' => 'error', 'message' => $e->getMessage()];
        }

        return ['status' => 'success', 'message' => 'Advertisement has been updated successfully'];
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $advertiseAttachment = AdvertiseAttachment::findOrFail($id);

        $advertisement = Advertisements::findOrFail($advertiseAttachment->advertisement_id);

        if ($advertisement->content_type == 1) {
            $filePath = public_path('uploads/advertisement/' . tenant('id') . '/' . 'file/' . $advertiseAttachment->image);
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        } else {
            $filePath = public_path('uploads/advertisement/' . tenant('id') . '/' . 'file/' . $advertiseAttachment->video);
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }

        $advertiseAttachment->delete();

        return ['status' => 'success', 'message' => 'Advertisement has been deleted successfully'];
    }
}
