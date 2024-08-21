<?php

namespace App\Interfaces\Hrm;

interface PayrollControllerMethodContainersInterface
{
    /**
     * @return \App\Services\Hrm\MethodContainerServices\PayrollControllerMethodContainersService
     */

    public function indexMethodContainer(object $request): object|array;

    public function showMethodContainer(int $id): ?array;

    public function printMethodContainer(int $id, object $request): ?array;

    public function createMethodContainer(object $request): ?array;

    public function storeMethodContainer(object $request, object $codeGenerator): void;

    public function editMethodContainer(int $id): ?array;

    public function updateMethodContainer(int $id, object $request): void;

    public function deleteMethodContainer(int $id): ?array;
}
