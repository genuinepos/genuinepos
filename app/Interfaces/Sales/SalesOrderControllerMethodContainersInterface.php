<?php

namespace App\Interfaces\Sales;

interface SalesOrderControllerMethodContainersInterface
{
    /**
     * @return \App\Services\Sales\MethodContainerServices\SalesOrderControllerMethodContainersService
     */

    public function showMethodContainer(
        int $id,
        object $salesOrderService,
        object $saleProductService
    ): ?array;

    function editMethodContainer(
        int $id,
        object $salesOrderService,
        object $accountService,
        object $accountFilterService,
        object $paymentMethodService,
        object $priceGroupService,
        object $managePriceGroupService,
    ): array;

    function updateMethodContainer(
        int $id,
        object $request,
        object $branchSettingService,
        object $saleService,
        object $salesOrderService,
        object $salesOrderProductService,
        object $dayBookService,
        object $accountService,
        object $accountLedgerService,
        object $accountingVoucherService,
        object $accountingVoucherDescriptionService,
        object $accountingVoucherDescriptionReferenceService,
        object $userActivityLogUtil,
        object $codeGenerator
    ): ?array;
}
