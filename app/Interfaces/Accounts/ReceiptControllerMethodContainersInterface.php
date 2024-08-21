<?php

namespace App\Interfaces\Accounts;

interface ReceiptControllerMethodContainersInterface
{
    /**
     * @return \App\Services\Accounts\MethodContainerServices\ReceiptControllerMethodContainersService
     */
    
    public function indexMethodContainer(object $request, $creditAccountId = null): object|array;

    public function showMethodContainer(int $id = null): ?array;

    public function printMethodContainer(int $id = null, object $request): ?array;

    public function createMethodContainer(int $creditAccountId = null): ?array;

    public function storeMethodContainer(object $request, object $codeGenerator): ?array;

    public function editMethodContainer(int $id, int $creditAccountId = null): ?array;

    public function updateMethodContainer(int $id, object $request): ?array;

    public function deleteMethodContainer(int $id): ?object;
}
