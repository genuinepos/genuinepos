<?php

namespace App\Interfaces\Accounts;

interface ContraControllerMethodContainersInterface
{
    /**
     * @return \App\Services\Accounts\MethodContainerServices\ContraControllerMethodContainersService
     */

    public function showMethodContainer(
        int $id,
        object $accountingVoucherService,
    ): ?array;

    public function printMethodContainer(
        int $id,
        object $request,
        object $accountingVoucherService,
    ): ?array;

    public function createMethodContainer(
        object $accountService,
        object $accountFilterService,
        object $paymentMethodService,
    ): ?array;

    public function storeMethodContainer(
        object $request,
        object $contraService,
        object $accountLedgerService,
        object $accountingVoucherService,
        object $accountingVoucherDescriptionService,
        object $dayBookService,
        object $codeGenerator,
    ): ?array;

    public function editMethodContainer(
        int $id,
        object $accountingVoucherService,
        object $accountService,
        object $accountFilterService,
        object $paymentMethodService,
    ): ?array;

    public function updateMethodContainer(
        int $id,
        object $request,
        object $contraService,
        object $accountLedgerService,
        object $accountingVoucherService,
        object $accountingVoucherDescriptionService,
        object $dayBookService,
    ): ?array;
}
