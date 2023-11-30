<?php

namespace Modules\SAAS\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\SAAS\Http\Requests\BusinessVerificationRequest;
use Modules\SAAS\Services\BusinessVerificationService;

class BusinessVerificationController extends Controller
{
    public function index()
    {
        return view('saas::guest.tenant-verification');
    }

    public function send(BusinessVerificationRequest $request, BusinessVerificationService $businessVerificationService)
    {
        $attributes = $request->validated();
        $businessVerificationService->sendVerificationEmail($attributes['email']);
        return back()->with('success', __('Verification email sent successfully. Go to your inbox and verify.'));
    }

    public function verify(Request $request)
    {

    }
}
