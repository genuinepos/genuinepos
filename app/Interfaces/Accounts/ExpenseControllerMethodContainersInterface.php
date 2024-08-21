<?php

namespace App\Interfaces\Accounts;

interface ExpenseControllerMethodContainersInterface
{
    /**
     * @return \App\Services\Accounts\MethodContainerServices\ExpenseControllerMethodContainersService
     */

    public function indexMethodContainer(object $request): object|array;

    public function showMethodContainer(int $id): ?array;

    public function printMethodContainer(int $id, object $request): ?array;

    public function createMethodContainer(): ?array;

    public function storeMethodContainer(object $request, object $codeGenerator): ?array;

    public function editMethodContainer(int $id): ?array;

    public function updateMethodContainer(int $id, object $request): ?array;

    public function deleteMethodContainer(int $id): void;
}
