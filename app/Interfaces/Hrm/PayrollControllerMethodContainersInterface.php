<?php

namespace App\Interfaces\Hrm;

interface PayrollControllerMethodContainersInterface
{
    public function createMethodContainer(
        object $accountService,
        object $accountFilterService,
        object $paymentMethodService,
    ): ?array;
}
