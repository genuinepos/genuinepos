<?php

namespace App\Interfaces\Sales;

interface SalesOrderControllerMethodContainersInterface
{
    /**
     * @return \App\Services\Sales\MethodContainerServices\SalesOrderControllerMethodContainersService
     */
    public function indexMethodContainer(object $request, ?int $customerAccountId): array|object;

    public function showMethodContainer(int $id): ?array;

    public function editMethodContainer(int $id): array;

    public function updateMethodContainer(int $id, object $request, object $codeGenerator): ?array;

    public function searchByOrderIdMethodContainer(string $keyWord): array|object;

    public function deleteMethodContainer(int $id): ?array;
}
