<?php

namespace Modules\SAAS\Interfaces;

interface EmailVerificationServiceInterface
{
    /**
     * @return \Modules\SAAS\Services\EmailVerificationService
     */

    public function storeEmailVerificationCode(string $email): object;
    public function singleEmailVerification(string $email, bool $isVerified): ?object;
}
