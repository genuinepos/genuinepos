<?php

namespace App\Interfaces\TransferStocks;

interface TransferStockControllerMethodContainersInterface
{
    /**
     * @return \App\Services\TransferStocks\MethodContainerServices\TransferStockControllerMethodContainersService
     */

    public function indexMethodContainer(object $request): array|object;

    public function showMethodContainer(int $id): ?array;

    public function printMethodContainer(int $id, object $request): ?array;

    public function createMethodContainer(): ?array;

    public function storeMethodContainer(object $request, object $codeGenerator): array;

    public function editMethodContainer(int $id): ?array;

    public function updateMethodContainer(int $id, object $request): void;

    public function deleteMethodContainer(int $id): ?array;
}
