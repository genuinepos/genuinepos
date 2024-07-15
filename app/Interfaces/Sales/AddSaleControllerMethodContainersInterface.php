<?php

namespace App\Interfaces\Sales;

interface AddSaleControllerMethodContainersInterface
{
    /**
     * @return \App\Services\Sales\MethodContainerServices\AddSaleControllerMethodContainersService
     */
    public function indexMethodContainer(object $request): array|object;

    public function createMethodContainer(object $codeGenerator): ?array;

    public function storeMethodContainer(object $request, object $codeGenerator): ?array;

    public function printTemplateBySaleStatus(object $request, object $sale, object $customerCopySaleProducts): mixed;

    public function showMethodContainer(int $id): ?array;

    public function editMethodContainer(int $id): array;

    public function updateMethodContainer(int $id, object $request, object $codeGenerator): ?array;

    public function deleteMethodContainer(int $id): array|object;

    public function searchByInvoiceIdMethodContainer(string $keyWord): array|object;
}
