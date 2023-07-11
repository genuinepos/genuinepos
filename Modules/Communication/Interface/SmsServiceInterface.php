<?php

namespace Modules\Communication\Interface;

interface SmsServiceInterface
{
    public function send(string $message, string $numbers);

    public function sendMultiple(string $message, array $numbers);
}
