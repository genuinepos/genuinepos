<?php

namespace App\Interfaces\Accounts;

interface ReceiptControllerMethodContainersInterface
{
    /**
     * @return \App\Services\Accounts\MethodContainerServices\ReceiptControllerMethodContainersService
     */

    public function showMethodContainer(
        int $id = null,
        object $accountingVoucherService,
    ): ?array;

    public function createMethodContainer(
        ?int $creditAccountId = null,
        object $accountService,
        object $accountFilterService,
        object $dayBookVoucherService,
        object $paymentMethodService,
    ): ?array;

    public function storeMethodContainer(
        object $request,
        object $receiptService,
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
        ?int $creditAccountId = null,
        object $accountingVoucherService,
        object $accountService,
        object $accountFilterService,
        object $dayBookVoucherService,
        object $paymentMethodService,
    ): ?array;

    public function updateMethodContainer(
        int $id,
        object $request,
        object $receiptService,
        object $branchSettingService,
        object $accountLedgerService,
        object $accountingVoucherService,
        object $accountingVoucherDescriptionService,
        object $accountingVoucherDescriptionReferenceService,
        object $dayBookService,
        object $saleService,
        object $purchaseReturnService,
    ): ?array;

    public function deleteMethodContainer(
        int $id,
        object $receiptService,
        object $saleService,
        object $salesReturnService,
        object $purchaseService,
        object $purchaseReturnService,
    ): ?object;
}
