<?php

namespace App\Http\Controllers\communication\sms;


use App\Http\Controllers\Controller;

use App\Services\Communication\Sms\SmsBodyService;

use Illuminate\Http\Request;

class SmsBodyController extends Controller
{

    public function __construct(private SmsBodyService $smsBodyService)
    {

    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return $this->smsBodyService->index($request);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $response = $this->smsBodyService->store($request->all());

        return response()->json($response);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $data = $this->smsBodyService->edit($id);
        return response()->json($data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $response = $this->smsBodyService->update($id, $request->all());

        return response()->json($response);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $response = $this->smsBodyService->destroy($id);

        return response()->json($response);
    }
}
