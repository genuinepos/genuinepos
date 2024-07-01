<?php

namespace App\Interfaces\Sales;

interface QuotationControllerMethodContainersInterface
{
    /**
     * @return \App\Services\Sales\MethodContainerServices\QuotationControllerMethodContainersService
     */
    public function indexMethodContainer(object $request, ?int $saleScreenType = null): object|array;

    public function showMethodContainer(int $id): ?array;

    public function editMethodContainer(int $id): array;

    public function updateMethodContainer(int $id, object $request, object $codeGenerator): ?array;

    public function editStatusMethodContainer(int $id): ?array;

    public function updateStatusMethodContainer(int $id, object $request, object $codeGenerator): ?array;

    public function deleteMethodContainer(int $id): ?array;
}
