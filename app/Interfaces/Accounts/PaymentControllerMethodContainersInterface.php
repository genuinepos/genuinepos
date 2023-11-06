<?php

namespace App\Interfaces\Accounts;

interface PaymentControllerMethodContainersInterface
{
    /**
     * @return \App\Services\Accounts\MethodContainerServices\PaymentControllerMethodContainersService
     */
    public function showMethodContainer(
        int $id,
        object $accountingVoucherService,
    ): ?array;

    public function createMethodContainer(
        int $debitAccountId = null,
        object $accountService,
        object $accountFilterService,
        object $dayBookVoucherService,
        object $paymentMethodService,
    ): ?array;

    public function storeMethodContainer(
        object $request,
        object $paymentService,
        object $branchSettingService,
        object $accountLedgerService,
        object $accountingVoucherService,
        object $accountingVoucherDescriptionService,
        object $accountingVoucherDescriptionReferenceService,
        object $dayBookService,
        object $codeGenerator,
    ): ?array;

    public function editMethodContainer(
        int $id,
        int $debitAccountId = null,
        object $accountingVoucherService,
        object $accountService,
        object $accountFilterService,
        object $dayBookVoucherService,
        object $paymentMethodService,
    ): ?array;

    public function updateMethodContainer(
        int $id,
        object $request,
        object $paymentService,
        object $branchSettingService,
        object $accountLedgerService,
        object $accountingVoucherService,
        object $accountingVoucherDescriptionService,
        object $accountingVoucherDescriptionReferenceService,
        object $dayBookService,
        object $purchaseService,
        object $salesReturnService,
    ): ?array;

    public function deleteMethodContainer(
        int $id,
        object $paymentService,
        object $saleService,
        object $salesReturnService,
        object $purchaseService,
        object $purchaseReturnService,
    ): ?object;
}
