<?php

namespace App\Interfaces\Purchases;

interface PurchaseReturnControllerMethodContainersInterface
{
    /**
     * @return \App\Services\Purchases\MethodContainerServices\PurchaseReturnControllerMethodContainersService
     */

     public function indexMethodContainer(object $request): array|object;

     public function showMethodContainer($id): ?array;

     public function printMethodContainer(object $request, int $id): ?array;

     public function createMethodContainer(object $codeGenerator): array;

     public function storeMethodContainer(object $request, object $codeGenerator): array;

     public function editMethodContainer(int $id): array;

     public function updateMethodContainer(int $id, object $request, object $codeGenerator): ?array;

     public function deleteMethodContainer(int $id): ?array;
}
