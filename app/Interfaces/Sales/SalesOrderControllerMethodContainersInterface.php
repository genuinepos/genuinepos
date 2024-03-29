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

    public function editMethodContainer(
        int $id,
        object $salesOrderService,
        object $accountService,
        object $accountFilterService,
        object $paymentMethodService,
        object $priceGroupService,
        object $managePriceGroupService,
    ): array;

    public function updateMethodContainer(
        int $id,
        object $request,
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
