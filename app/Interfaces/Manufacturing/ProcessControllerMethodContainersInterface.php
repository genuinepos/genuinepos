<?php

namespace App\Interfaces\Manufacturing;

interface ProcessControllerMethodContainersInterface
{
    /**
     * @return \App\Services\Manufacturing\MethodContainerServices\ProcessControllerMethodContainersService
     */

    public function indexMethodContainer(object $request): ?object;
    public function showMethodContainer(int $id): array;
    public function printMethodContainer(int $id, object $request): array;
    public function selectProductModalMethodContainer(): array;
    public function createMethodContainer(object $request) : mixed;
    public function storeMethodContainer(object $request) : void;
    public function editMethodContainer(int $id) : array;
    public function updateMethodContainer(int $id, object $request) : void;
    public function deleteMethodContainer(int $id) : void;
}
