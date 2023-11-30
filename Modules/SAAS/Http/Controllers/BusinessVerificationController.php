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
        if (! hash_equals((string) $request->route('id'), (string) $request->user()->getKey())) {
            throw new AuthorizationException;
        }

        if (! hash_equals((string) $request->route('hash'), sha1($request->user()->getEmailForVerification()))) {
            throw new AuthorizationException;
        }

        if ($request->user()->hasVerifiedEmail()) {
            return $request->wantsJson()
                        ? new JsonResponse([], 204)
                        : redirect($this->redirectPath());
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        if ($response = $this->verified($request)) {
            return $response;
        }

        return $request->wantsJson()
                    ? new JsonResponse([], 204)
                    : redirect($this->redirectPath())->with('verified', true);
    }
}
