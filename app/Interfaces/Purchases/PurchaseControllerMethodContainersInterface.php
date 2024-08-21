<?php

namespace App\Interfaces\Purchases;

interface PurchaseControllerMethodContainersInterface
{
    /**
     * @return \App\Services\Purchases\MethodContainerServices\PurchaseControllerMethodContainersService
     */

    public function indexMethodContainer(object $request, ?int $supplierAccountId = null): array|object;

    public function showMethodContainer($id): ?array;

    public function printMethodContainer(object $request, int $id): ?array;

    public function createMethodContainer(object $codeGenerator): array;

    public function storeMethodContainer(object $request, object $codeGenerator): array;

    public function editMethodContainer(int $id): array;

    public function updateMethodContainer(int $id, object $request, object $codeGenerator): ?array;

    public function deleteMethodContainer(int $id): ?array;

    public function searchPurchasesByInvoiceIdMethodContainer(int $keyWord): array|object;

    public function purchaseInvoiceIdMethodContainer(object $codeGenerator): string;
}
