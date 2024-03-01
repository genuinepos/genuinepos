<?php

namespace Modules\SAAS\Http\Controllers\Guest;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Modules\SAAS\Interfaces\EmailVerificationServiceInterface;

class SendEmailController extends Controller
{
    public function __construct(
        private EmailVerificationServiceInterface $emailVerificationServiceInterface
    ) {
    }

    /**
     * Display a listing of the resource.
     */
    public function sendVerificationCode(Request $request)
    {
        $storeEmailVerificationCode = $this->emailVerificationServiceInterface->storeEmailVerificationCode(email: $request->email);

        dispatch(new \Modules\SAAS\Jobs\SendGuestVerificationCodeEmailQueueJob(to: $request->email, code: $storeEmailVerificationCode->code));

        return true;
    }

    public function emailVerificationCodeMatch(Request $request)
    {
        $matchAndUpdateEmailVerification = $this->emailVerificationServiceInterface->matchAndUpdateEmailVerification(request: $request);
       
        if ($matchAndUpdateEmailVerification) {

            return true;
        } else {

            return false;
        }
    }
}
