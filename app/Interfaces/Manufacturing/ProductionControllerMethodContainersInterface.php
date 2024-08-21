<?php

namespace App\Interfaces\Manufacturing;

interface ProductionControllerMethodContainersInterface
{
    /**
     * @return \App\Services\Manufacturing\MethodContainerServices\ProductionControllerMethodContainersService
     */

    public function indexMethodContainer(object $request): array|object;

    public function showMethodContainer(int $id): ?array;

    public function printMethodContainer(int $id, object $request): ?array;

    public function createMethodContainer(): ?array;

    public function storeMethodContainer(object $request, object $codeGenerator): ?array;

    public function editMethodContainer(int $id): ?array;

    public function updateMethodContainer(int $id, object $request): ?array;

    public function deleteMethodContainer(int $id): ?array;
}
