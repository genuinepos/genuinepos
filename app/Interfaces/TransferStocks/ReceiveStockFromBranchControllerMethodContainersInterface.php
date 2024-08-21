<?php

namespace App\Interfaces\TransferStocks;

interface ReceiveStockFromBranchControllerMethodContainersInterface
{
    /**
     * @return \App\Services\TransferStocks\MethodContainerServices\ReceiveStockFromBranchControllerMethodContainersService
     */

    public function indexMethodContainer(object $request): ?object;

    public function createMethodContainer(int $id): array;

    public function receiveMethodContainer(int $id, object $request): void;
}
