<?php

namespace App\Http\Controllers\advertisement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Advertisement\AdvertisementService;

class AdvertiseMentController extends Controller
{

    public function __construct(private AdvertisementService $advertisementService)
    {

    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return $this->advertisementService->index($request);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return $this->advertisementService->create();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $response = $this->advertisementService->store($request);

        return response()->json($response);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

        $data = $this->advertisementService->show($id);
        if($data[0]->image){
          return view('advertisement.show_image',compact('data'));
        }else{
          return view('advertisement.show_video',compact('data'));
        }
    
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = $this->advertisementService->edit($id);
        return view('advertisement.edit',compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $response = $this->advertisementService->update($id, $request->all());

        return response()->json($response);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
       
    }
}
