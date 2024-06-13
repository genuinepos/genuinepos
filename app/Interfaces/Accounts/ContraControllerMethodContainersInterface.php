<?php

namespace App\Interfaces\Accounts;

interface ContraControllerMethodContainersInterface
{
    /**
     * @return \App\Services\Accounts\MethodContainerServices\ContraControllerMethodContainersService
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
