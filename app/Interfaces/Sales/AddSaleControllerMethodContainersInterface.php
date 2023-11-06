<?php

namespace App\Interfaces\Sales;

interface AddSaleControllerMethodContainersInterface
{
    /**
     * @return \App\Services\Sales\MethodContainerServices\AddSaleControllerMethodContainersService
     */
    public function createMethodContainer(
        object $branchService,
        object $accountService,
        object $accountFilterService,
        object $paymentMethodService,
        object $warehouseService,
        object $priceGroupService,
        object $managePriceGroupService,
    ): ?array;

    public function storeMethodContainer(
        object $request,
        object $branchSettingService,
        object $saleService,
        object $saleProductService,
        object $dayBookService,
        object $accountService,
        object $accountLedgerService,
        object $productStockService,
        object $productLedgerService,
        object $purchaseProductService,
        object $accountingVoucherService,
        object $accountingVoucherDescriptionService,
        object $accountingVoucherDescriptionReferenceService,
        object $userActivityLogUtil,
        object $codeGenerator
    ): ?array;

    public function showMethodContainer(
        int $id,
        object $saleService,
        object $saleProductService
    ): ?array;

    public function editMethodContainer(
        int $id,
        object $branchService,
        object $saleService,
        object $accountService,
        object $accountFilterService,
        object $paymentMethodService,
        object $warehouseService,
        object $priceGroupService,
        object $managePriceGroupService,
    ): array;

    public function updateMethodContainer(
        int $id,
        object $request,
        object $branchSettingService,
        object $saleService,
        object $saleProductService,
        object $dayBookService,
        object $accountService,
        object $accountLedgerService,
        object $productStockService,
        object $productLedgerService,
        object $purchaseProductService,
        object $accountingVoucherService,
        object $accountingVoucherDescriptionService,
        object $accountingVoucherDescriptionReferenceService,
        object $userActivityLogUtil,
        object $codeGenerator
    ): ?array;

    public function deleteMethodContainer(
        int $id,
        object $saleService,
        object $productStockService,
        object $purchaseProductService,
        object $userActivityLogUtil,
    ): array|object;
}
