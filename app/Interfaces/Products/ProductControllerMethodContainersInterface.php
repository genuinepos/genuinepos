<?php

namespace App\Interfaces\Products;

interface ProductControllerMethodContainersInterface
{
    /**
     * @return \App\Services\Products\MethodContainerServices\ProductControllerMethodContainersService
     */

    public function indexMethodContainer(object $request, int $isForCreatePage = 0): array|object;
    public function showMethodContainer(int $id): array;
    public function createMethodContainer(object $request, ?int $id): array|object;
    public function storeMethodContainer(object $request): array|object;
    public function editMethodContainer(int $id): array;
    public function updateMethodContainer(int $id, object $request): ?array;
    public function formPartMethodContainer(int $type): array;
    public function changeStatusMethodContainer(int $id): array;
    public function deleteMethodContainer(int $id): ?array;
    public function getLastProductIdMethodContainer(): string;
}
