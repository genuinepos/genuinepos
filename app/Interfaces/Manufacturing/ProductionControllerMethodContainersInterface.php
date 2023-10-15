<?php

namespace App\Interfaces\Manufacturing;

interface ProductionControllerMethodContainersInterface
{
    /**
     * @return \App\Services\Manufacturing\MethodContainerServices\ProductionControllerMethodContainersService
     */

     public function showMethodContainer(
        int $id,
        object $productionService,
    ): ?array;

     public function createMethodContainer(
        object $warehouseService,
        object $accountService,
        object $processService,
    ): ?array;

    public function storeMethodContainer(
        object $request,
        object $productionService,
        object $productionIngredientService,
        object $manufacturingSettingService,
        object $productService,
        object $productLedgerService,
        object $productStockService,
        object $purchaseProductService,
        object $dayBookService,
        object $codeGenerator,
    ): ?array;

    public function editMethodContainer(
        int $id,
        object $productionService,
        object $warehouseService,
        object $accountService,
        object $processService,
    ): ?array;

    public function updateMethodContainer(
        int $id,
        object $request,
        object $productionService,
        object $productionIngredientService,
        object $manufacturingSettingService,
        object $productService,
        object $productLedgerService,
        object $productStockService,
        object $purchaseProductService,
        object $dayBookService,
    ): ?array;

    public function deleteMethodContainer(
        int $id,
        object $productionService,
        object $productStockService,
    ): ?array;
}
