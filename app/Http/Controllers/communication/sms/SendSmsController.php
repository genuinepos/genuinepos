<?php

namespace App\Http\Controllers\communication\sms;

use App\Http\Controllers\Controller;
use App\Services\Communication\Sms\SmsSendService;
use Illuminate\Http\Request;

class SendSmsController extends Controller
{

    public function __construct(private SmsSendService $smsSendService)
    {

    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        return $this->smsSendService->index($request);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $response = $this->smsSendService->store($request);

        return response()->json($response);

    }

    /**
     * Restore the specified resource from storage.
     */
    public function restoreSms(string $id)
    {
        $response = $this->smsSendService->restore($id);

        return response()->json($response);

    }

    /**
     * Remove the specified resource from storage.
     */

    public function destroy(string $id)
    {
        $response = $this->smsSendService->destroy($id);

        return response()->json($response);

    }

    public function deleteSmsMultiple(Request $request)
    {

        $response = $this->smsSendService->deleteSmsMultiple($request);

        return response()->json($response);
    }

    public function deleteSmsPermanent(Request $request)
    {

        $response = $this->smsSendService->deleteSmsPermanent($request);

        return response()->json($response);
    }
}
