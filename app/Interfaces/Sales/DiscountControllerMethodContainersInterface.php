<?php

namespace App\Interfaces\Sales;

interface DiscountControllerMethodContainersInterface
{
    /**
     * @return \App\Services\Sales\MethodContainerServices\DiscountControllerMethodContainersService
     */
    public function indexMethodContainer(object $request): ?object;

    public function createMethodContainer(): array;

    public function storeMethodContainer(object $request): ?array;

    public function editMethodContainer(int $id): array;

    public function updateMethodContainer(int $id, object $request): ?array;

    public function deleteMethodContainer(int $id): void;

    public function changeStatusMethodContainer(int $id): void;
}
