<?php

namespace App\Interfaces\StockAdjustments;

interface StockAdjustmentControllerMethodContainersInterface
{
    /**
     * @return \App\Services\StockAdjustments\MethodContainerServices\StockAdjustmentControllerMethodContainersService
     */

    public function showMethodContainer(
        int $id,
        object $stockAdjustmentService,
    ): ?array;

    public function createMethodContainer(
        object $branchService,
        object $accountService,
        object $accountFilterService,
        object $paymentMethodService,
        object $warehouseService,
    ): ?array;

    public function storeMethodContainer(
        object $request,
        object $branchSettingService,
        object $stockAdjustmentService,
        object $stockAdjustmentProductService,
        object $dayBookService,
        object $accountLedgerService,
        object $productStockService,
        object $productLedgerService,
        object $accountingVoucherService,
        object $accountingVoucherDescriptionService,
        object $accountingVoucherDescriptionReferenceService,
        object $userActivityLogUtil,
        object $codeGenerator
    ): ?array;

    function deleteMethodContainer(
        int $id,
        object $stockAdjustmentService,
        object $productStockService,
        object $userActivityLogUtil,
    ): ?array;
}
