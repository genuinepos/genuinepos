<?php

namespace App\Interfaces\Sales;

interface DraftControllerMethodContainersInterface
{
    /**
     * @return \App\Services\Sales\MethodContainerServices\DraftControllerMethodContainersService
     */
    public function indexMethodContainer(object $request): object|array;

    public function showMethodContainer(int $id): ?array;

    public function editMethodContainer(int $id): array;

    public function updateMethodContainer(int $id, object $request, object $codeGenerator): ?array;
}
