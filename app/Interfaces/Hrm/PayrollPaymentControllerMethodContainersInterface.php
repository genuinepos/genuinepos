<?php

namespace App\Interfaces\Hrm;

interface PayrollPaymentControllerMethodContainersInterface
{
    /**
     * @return \App\Services\Hrm\MethodContainerServices\PayrollPaymentControllerMethodContainersService
     */

    public function showMethodContainer(
        int $id,
        object $accountingVoucherService
    ): ?array;

    public function printMethodContainer(
        int $id,
        object $request,
        object $accountingVoucherService
    ): ?array;

    public function createMethodContainer(
        int $payrollId,
        object $payrollService,
        object $accountService,
        object $accountFilterService,
        object $paymentMethodService,
    ): ?array;

    public function storeMethodContainer(
        object $request,
        object $payrollPaymentService,
        object $payrollService,
        object $accountingVoucherService,
        object $accountingVoucherDescriptionService,
        object $accountingVoucherDescriptionReferenceService,
        object $dayBookService,
        object $accountLedgerService,
        object $codeGenerator,
    ): ?array;

    public function editMethodContainer(
        int $id,
        object $accountingVoucherService,
        object $accountService,
        object $accountFilterService,
        object $paymentMethodService,
    ): ?array;

    public function updateMethodContainer(
        int $id,
        object $request,
        object $payrollPaymentService,
        object $payrollService,
        object $accountingVoucherService,
        object $accountingVoucherDescriptionService,
        object $accountingVoucherDescriptionReferenceService,
        object $dayBookService,
        object $accountLedgerService,
    ): ?array;

    public function deleteMethodContainer(
        int $id,
        object $payrollPaymentService,
        object $payrollService,
    ): void;
}
