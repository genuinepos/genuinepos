<?php

namespace Modules\Communication\Service;

use Modules\Communication\Interface\EmailServiceInterface;
use Modules\Communication\Jobs\BulkEmailSenderJob;

class EmailService implements EmailServiceInterface
{
    public function send(string $address, $mailObject)
    {
        BulkEmailSenderJob::dispatchAfterResponse($address, $mailObject);
    }

    public function sendMultiple(array $addressArray, $mailObject)
    {
        BulkEmailSenderJob::dispatchAfterResponse($addressArray, $mailObject);
        // ->delay(now()->addSecond(2));
    }
}
