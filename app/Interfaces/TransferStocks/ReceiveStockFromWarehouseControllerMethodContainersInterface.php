<?php

namespace App\Interfaces\TransferStocks;

interface ReceiveStockFromWarehouseControllerMethodContainersInterface
{
    /**
     * @return \App\Services\TransferStocks\MethodContainerServices\ReceiveStockFromWarehouseControllerMethodContainersService
     */

    public function indexMethodContainer(object $request): array|object;

    public function createMethodContainer(int $id): array;

    public function receiveMethodContainer(int $id, object $request): void;
}
