<?php

namespace App\Interfaces\StockAdjustments;

interface StockAdjustmentControllerMethodContainersInterface
{
    /**
     * @return \App\Services\StockAdjustments\MethodContainerServices\StockAdjustmentControllerMethodContainersService
     */
    public function indexMethodContainer(object $request): array|object;

    public function showMethodContainer(int $id): ?array;

    public function printMethodContainer(int $id, object $request): ?array;

    public function createMethodContainer(): ?array;

    public function storeMethodContainer(object $request, object $codeGenerator): ?array;

    public function deleteMethodContainer(int $id): ?array;
}
