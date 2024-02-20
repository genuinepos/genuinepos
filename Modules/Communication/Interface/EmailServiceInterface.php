<?php

namespace Modules\Communication\Interface;

interface EmailServiceInterface
{
    public function send(string $address, $mailObject);

    public function sendMultiple(array $addressArray, $mailObject);
}
