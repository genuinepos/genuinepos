<?php

namespace App\Services\Advertisement;

use App\Models\Advertisement\Advertisements;
use App\Models\Advertisement\AdvertiseAttachment;

use Yajra\DataTables\Facades\DataTables;

class AdvertisementService {

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
                            $imageUrl = asset('slider/image/'.$attachment->image);
                            if (file_exists(public_path('slider/image/'.$attachment->image))) {
                                $html .= '<img width="100px" height="100px" src="' . $imageUrl . '" />';
                            } else {
                                $html .= 'Image not found: ' . $imageUrl;
                            }
                        } else {
                            // Display video
                            $videoUrl = asset('slider/video/'.$attachment->video);
                            if (file_exists(public_path('slider/video/'.$attachment->video))) {
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
        ]);

        if ($request->content_type==1) {
            // $request->validate([
            //     'image' => 'required',
            //     'image.*' => 'required|mimes:jpeg,jpg,png,gif'
            // ]);
        }

        elseif ($request->content_type==2) {
            $request->validate([
                'video' => 'required',
            ]);
        }

        $advertisement = Advertisements::create([
            'content_type' => $request->content_type,
            'title' => $request->title,
            'status' => $request->status,
        ]);

        if ($request->hasFile('image')) {
            foreach ($request->file('image') as $key => $image) {
                $imageName = time().'.'.$image->getClientOriginalName(); 
                $image->move(public_path('slider/image'), $imageName);
                $data = [
                    'advertisement_id' => $advertisement->id,
                    'content_title' => $request->content_title[$key],
                    'caption' => $request->caption[$key],
                    'image' => $imageName,
                ];
                AdvertiseAttachment::create($data);
            }
        } else {
                $imageName = time().'.'.$request->video->getClientOriginalName(); 
                $request->video->move(public_path('slider/video'), $imageName);
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
        $data = AdvertiseAttachment::where('advertisement_id',$id)->get();
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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}