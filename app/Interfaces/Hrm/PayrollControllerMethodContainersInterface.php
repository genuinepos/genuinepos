<?php

namespace App\Interfaces\Hrm;

interface PayrollControllerMethodContainersInterface
{
    /**
     * @return \App\Services\Hrm\MethodContainerServices\PayrollControllerMethodContainersService
     */

    public function showMethodContainer(
        int $id,
        object $payrollService,
    ): ?array;

    public function createMethodContainer(
        object $request,
        object $payrollService,
        object $accountService,
        object $userService,
    ): ?array;

    public function storeMethodContainer(
        object $request,
        object $payrollService,
        object $payrollAllowanceService,
        object $payrollDeductionService,
        object $dayBookService,
        object $codeGenerator,
    ): void;

    public function editMethodContainer(
        int $id,
        object $payrollService,
        object $accountService,
    ): ?array;

    public function updateMethodContainer(
        int $id,
        object $request,
        object $payrollService,
        object $payrollAllowanceService,
        object $payrollDeductionService,
        object $dayBookService,
    ): void;

    public function deleteMethodContainer(
        int $id,
        object $payrollService,
    ): ?array;
}
