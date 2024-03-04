<?php

namespace App\Http\Controllers\email;

use App\Http\Controllers\Controller;

use App\Services\Email\EmailBodyService;

use Illuminate\Http\Request;

class EmailBodyController extends Controller
{

    public function __construct(private EmailBodyService $emailBodyService)
    {

    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return $this->emailBodyService->index($request);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $response = $this->emailBodyService->store($request->all());

        return response()->json($response);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $data = $this->emailBodyService->edit($id);
        return response()->json($data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $response = $this->emailBodyService->update($id, $request->all());

        return response()->json($response);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $response = $this->emailBodyService->destroy($id);

        return response()->json($response);
    }
}
