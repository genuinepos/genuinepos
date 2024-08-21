<?php

namespace Modules\SAAS\Services;

use App\Enums\BooleanType;
use Modules\SAAS\Entities\EmailVerification;
use Modules\SAAS\Jobs\SendGuestVerificationCodeEmailQueueJob;
use Modules\SAAS\Interfaces\EmailVerificationServiceInterface;

class EmailVerificationService implements EmailVerificationServiceInterface
{
    public function storeEmailVerificationCodeAndSendCode(string $email): void
    {
        $verification = $this->singleEmailVerification(email: $email, isVerified: false);

        if (!isset($verification)) {

            $addEmailVerification = new EmailVerification();
            $addEmailVerification->email = $email;
            $addEmailVerification->code = sprintf("%06d", mt_rand(1, 999999));
            $addEmailVerification->save();

            $verification = $addEmailVerification;
        }

        dispatch(new SendGuestVerificationCodeEmailQueueJob(to: $email, code: $verification->code));
    }

    public function matchAndUpdateEmailVerification(object $request): bool
    {
        $match = $this->verificationCodeMatch(email: $request->email, code: $request->code);

        if (isset($match)) {

            $match->is_verified = BooleanType::True->value;
            $match->save();

            return true;
        }

        return false;
    }

    public function singleEmailVerification(string $email, bool $isVerified): ?object
    {
        return EmailVerification::where('email', $email)->where('is_verified', $isVerified)->first();
    }

    public function verificationCodeMatch(string $email, int|string $code): ?object
    {
        return EmailVerification::where('email', $email)->where('code', $code)->first();
    }
}
