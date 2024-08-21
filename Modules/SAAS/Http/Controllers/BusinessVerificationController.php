<?php

namespace Modules\SAAS\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Modules\SAAS\Notifications\VerifyEmail;
use Illuminate\Auth\Access\AuthorizationException;
use Modules\SAAS\Services\BusinessVerificationService;
use Modules\SAAS\Http\Requests\BusinessVerificationRequest;

class BusinessVerificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('signed')->only('verify');
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }

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

    public function resend(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return $request->wantsJson()
                        ? new JsonResponse([], 204)
                        : redirect($this->redirectPath());
        }

        $request->user()->notify(new VerifyEmail);

        // $request->user()->sendEmailVerificationNotification();

        return $request->wantsJson()
                    ? new JsonResponse([], 202)
                    : back()->with('resent', true);
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
