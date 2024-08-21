<?php

namespace App\Interfaces\Sales;

interface SalesReturnControllerMethodContainersInterface
{
    /**
     * @return \App\Services\Sales\MethodContainerServices\SalesReturnControllerMethodContainersService
     */
    public function indexMethodContainer(object $request): array|object;

    public function showMethodContainer(int $id): ?array;

    public function printMethodContainer(int $id, object $request): mixed;

    public function createMethodContainer(object $codeGenerator): ?array;

    public function storeMethodContainer(object $request, object $codeGenerator): ?array;

    public function editMethodContainer(int $id): array;

    public function updateMethodContainer(int $id, object $request, object $codeGenerator): ?array;

    public function deleteMethodContainer(int $id): array|object;

    public function voucherNoMethodContainer(object $codeGenerator): string;
}
