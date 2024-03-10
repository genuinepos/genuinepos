<?php

namespace App\Http\Controllers\communication\email;

use App\Http\Controllers\Controller;
use App\Services\Communication\Email\EmailSendService;
use Illuminate\Http\Request;

class SendEmailController extends Controller
{

    public function __construct(private EmailSendService $emailSendService)
    {

    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        return $this->emailSendService->index($request);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $response = $this->emailSendService->store($request);

        return response()->json($response);

    }

    /**
     * Restore the specified resource from storage.
     */
    public function restoreEmail(string $id)
    {
        $response = $this->emailSendService->restore($id);

        return response()->json($response);

    }

    /**
     * Remove the specified resource from storage.
     */

    public function destroy(string $id)
    {
        $response = $this->emailSendService->destroy($id);

        return response()->json($response);

    }

    public function deleteEmailMultiple(Request $request)
    {
        $response = $this->emailSendService->deleteEmailMultiple($request);

        return response()->json($response);
    }

    public function deleteEmailPermanent(Request $request)
    {
        $response = $this->emailSendService->deleteEmailPermanent($request);

        return response()->json($response);
    }
}
