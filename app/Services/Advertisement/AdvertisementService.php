<?php

namespace App\Services\Advertisement;

use App\Models\Advertisement\Advertisements;

use Yajra\DataTables\Facades\DataTables;

class AdvertisementService {

     /**
     * Display a listing of the resource.
     */
    public function index($request)
    {
       $data = Advertisements::query()->orderBy('id', 'desc');

        if ($request->ajax()) {
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('status', function ($row) {
                    return $row->status == 1 ? "Active" : "Inactive";
                })
                ->addColumn('action', function ($row) {
                    $editBtn = '<a href="#" class="edit-btn btn btn-success btn-sm text-white" title="Edit" data-id="' . $row->id . '"><span class="fas fa-edit"></span></a>';
                    return $editBtn;

                })
                ->make(true);
        }

        return view('advertisement.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
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