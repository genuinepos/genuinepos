<?php

namespace App\Interfaces\Sales;

interface QuotationControllerMethodContainersInterface
{
    /**
     * @return \App\Services\Sales\MethodContainerServices\QuotationControllerMethodContainersService
     */

    public function showMethodContainer(
        int $id,
        object $quotationService,
        object $saleProductService
    ): ?array;

    function editMethodContainer(
        int $id,
        object $quotationService,
        object $accountService,
        object $accountFilterService,
        object $paymentMethodService,
        object $priceGroupService,
        object $managePriceGroupService
    ): array;

    function updateMethodContainer(
        int $id,
        object $request,
        object $branchSettingService,
        object $saleService,
        object $quotationService,
        object $salesOrderService,
        object $quotationProductService,
        object $accountService,
        object $accountLedgerService,
        object $accountingVoucherService,
        object $accountingVoucherDescriptionService,
        object $accountingVoucherDescriptionReferenceService,
        object $userActivityLogUtil,
        object $codeGenerator,
    ): ?array;
}
