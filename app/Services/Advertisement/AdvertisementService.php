<?php

namespace App\Services\Advertisement;

use App\Models\Advertisement\Advertisements;
use App\Models\Advertisement\AdvertiseAttachment;
use Yajra\DataTables\Facades\DataTables;
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

        // $logo = null;

        // if ($request->hasFile('logo')) {
        //     $logo = $this->single($request, 'slider/logo', 'logo');
        // }

        $advertisement = Advertisements::create([
            'content_type' => $request->content_type,
            'title' => $request->title,
            // 'logo' => $logo,
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
        $data = Advertisements::with('attachments')->findOrFail($id);
        return $data;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($request,$id)
    {

        $advertisement = Advertisements::findOrFail($id);

        $validatedData = $request->validate([
            'title' => 'required',
            'content_type' => 'required',
            'status' => 'required',
            'logo' => 'mimes:jpeg,jpg,png,gif,avif,webp|max:1024', // 1 MB max
        ]);

        // if ($request->content_type == 1) {
        //     $request->validate([
        //         'image' => 'array|min:1', // At least one image is required
        //         'image.*' => 'mimes:jpeg,jpg,png,gif,avif,webp|max:1024', // 1 MB max
        //     ]);
        // } elseif ($request->content_type == 2) {
        //     $request->validate([
        //         'video' => 'mimes:mp4,avi,mov,wmv|max:102400', // 100 MB max
        //     ], [
        //         'video.max' => 'The video must not be larger than 100 MB.',
        //     ]);
        // }

        $advertisement->update([
            'content_type' => $request->content_type,
            'title' => $request->title,
            'status' => $request->status,
        ]);

        dd('update');

        // Handle logo update if present
        // if ($request->hasFile('logo')) {
        //     $logo = $this->single($request, 'slider/logo', 'logo');
        //     $advertisement->update(['logo' => $logo]);
        // }

        // Handle image or video update
        if ($request->content_type == 1) {
            $this->deleteExistingAttachments($advertisement);
            foreach ($request->file('image') as $key => $image) {
                $imageName = $this->multiple($request->file('image'), '/uploads/advertisement/' . tenant('id') . '/file');
                $data = [
                    'advertisement_id' => $advertisement->id,
                    'content_title' => $request->input('content_title')[$key],
                    'caption' => $request->input('caption')[$key],
                    'image' => $imageName,
                ];
                AdvertiseAttachment::where('advertisement_id', $id)->update($data);
            }
        } elseif ($request->content_type == 2) {
            $this->deleteExistingAttachments($advertisement);
            $imageName = $this->single($request, '/uploads/advertisement/' . tenant('id') . '/file', 'video');
            $data = [
                'advertisement_id' => $advertisement->id,
                'video' => $imageName,
            ];
            AdvertiseAttachment::where('advertisement_id', $id)->update($data);
        }

        return ['status' => 'success', 'message' => 'Advertisement has been updated successfully'];
    }


    protected function deleteExistingAttachments(Advertisements $advertisement)
    {
        $existingAttachments = AdvertiseAttachment::where('advertisement_id', $advertisement->id)->get();

        foreach ($existingAttachments as $attachment) {

            // Delete image files
            if ($advertisement->content_type == 1) {
                if (!empty($attachment->image)) {
                    $imagePath = public_path('uploads/advertisement/' . tenant('id') . '/file/' . $attachment->image);
                    if (file_exists($imagePath)) {
                        unlink($imagePath);
                    }
                }
            }

            // Delete video files
            if ($advertisement->content_type == 2) {
                if (!empty($attachment->video)) {
                    $videoPath = public_path('uploads/advertisement/' . tenant('id') . '/file/' . $attachment->video);
                    if (file_exists($videoPath)) {
                        unlink($videoPath);
                    }
                }
            }

            // Delete the attachment record from the database
            $attachment->delete();
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
