<?php

namespace App\Interfaces\Sales;

interface DraftControllerMethodContainersInterface
{
    /**
     * @return \App\Services\Sales\MethodContainerServices\DraftControllerMethodContainersService
     */
    public function showMethodContainer(
        int $id,
        object $draftService,
        object $saleProductService
    ): ?array;

    public function editMethodContainer(
        int $id,
        object $draftService,
        object $branchService,
        object $accountService,
        object $accountFilterService,
        object $paymentMethodService,
        object $priceGroupService,
        object $warehouseService,
        object $managePriceGroupService,
    ): array;

    public function updateMethodContainer(
        int $id,
        object $request,
        object $saleService,
        object $draftService,
        object $draftProductService,
        object $branchSettingService,
        object $dayBookService,
        object $accountLedgerService,
        object $productStockService,
        object $productLedgerService,
        object $purchaseProductService,
        object $accountService,
        object $accountingVoucherService,
        object $accountingVoucherDescriptionService,
        object $accountingVoucherDescriptionReferenceService,
        object $userActivityLogUtil,
        object $codeGenerator,
    ): ?array;
}
