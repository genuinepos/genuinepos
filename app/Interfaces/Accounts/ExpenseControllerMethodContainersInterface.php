<?php

namespace App\Interfaces\Accounts;

interface ExpenseControllerMethodContainersInterface
{
    /**
     * @return \App\Services\Accounts\MethodContainerServices\ExpenseControllerMethodContainersService
     */

    public function showMethodContainer(
        int $id,
        object $accountingVoucherService,
    ): ?array;

    public function createMethodContainer(
        object $accountService,
        object $accountFilterService,
        object $paymentMethodService,
    ): ?array;

    public function storeMethodContainer(
        object $request,
        object $expenseService,
        object $branchSettingService,
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
        object $expenseService,
        object $branchSettingService,
        object $accountLedgerService,
        object $accountingVoucherService,
        object $accountingVoucherDescriptionService,
        object $dayBookService,
    ): ?array;
}
