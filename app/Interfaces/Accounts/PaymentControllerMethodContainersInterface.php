<?php

namespace App\Interfaces\Accounts;

interface PaymentControllerMethodContainersInterface
{
    /**
     * @return \App\Services\Accounts\MethodContainerServices\PaymentControllerMethodContainersService
     */
    
    public function indexMethodContainer(object $request, ?int $debitAccountId = null): object|array;

    public function showMethodContainer(int $id): ?array;

    public function printMethodContainer(int $id, object $request): ?array;

    public function createMethodContainer(int $debitAccountId = null): ?array;

    public function storeMethodContainer(object $request, object $codeGenerator): ?array;

    public function editMethodContainer(int $id, int $debitAccountId = null): ?array;

    public function updateMethodContainer(int $id, object $request): ?array;

    public function deleteMethodContainer(int $id): ?object;
}
