<?php

namespace App\Interfaces\Hrm;

interface PayrollPaymentControllerMethodContainersInterface
{
    /**
     * @return \App\Services\Hrm\MethodContainerServices\PayrollPaymentControllerMethodContainersService
     */

    public function showMethodContainer(int $id): ?array;

    public function printMethodContainer(int $id, object $request): ?array;

    public function createMethodContainer(int $payrollId): ?array;

    public function storeMethodContainer(object $request, object $codeGenerator): ?array;

    public function editMethodContainer(int $id): ?array;

    public function updateMethodContainer(int $id, object $request): ?array;

    public function deleteMethodContainer(int $id): void;
}
