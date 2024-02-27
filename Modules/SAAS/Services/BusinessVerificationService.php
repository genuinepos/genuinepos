<?php

namespace Modules\SAAS\Services;

use App\Models\User;
use Exception;
use Modules\SAAS\Notifications\VerifyEmail;

class BusinessVerificationService
{
    public function sendVerificationEmail(string $email)
    {
        $user = User::whereEmail($email)->first();

        if (!$user) {

            throw new Exception('User not found with provided email');
        }
        
        $user->notify(new VerifyEmail);
    }
}
