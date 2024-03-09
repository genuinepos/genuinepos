<?php

namespace Modules\SAAS\Interfaces;

interface EmailVerificationServiceInterface
{
    /**
     * @return \Modules\SAAS\Services\EmailVerificationService
     */

    public function storeEmailVerificationCodeAndSendCode(string $email): void;
    public function singleEmailVerification(string $email, bool $isVerified): ?object;
}
