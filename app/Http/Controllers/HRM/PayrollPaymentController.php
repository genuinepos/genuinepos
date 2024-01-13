<?php

namespace App\Http\Controllers\HRM;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\Hrm\PayrollService;
use App\Services\Accounts\AccountService;
use App\Services\Accounts\DayBookService;
use App\Services\Hrm\PayrollPaymentService;
use App\Services\Setups\PaymentMethodService;
use App\Services\Accounts\AccountFilterService;
use App\Services\Accounts\AccountLedgerService;
use App\Interfaces\CodeGenerationServiceInterface;
use App\Services\Accounts\AccountingVoucherService;
use App\Services\Accounts\AccountingVoucherDescriptionService;
use App\Services\Accounts\AccountingVoucherDescriptionReferenceService;
use App\Interfaces\Hrm\PayrollPaymentControllerMethodContainersInterface;

class PayrollPaymentController extends Controller
{
    public function __construct(
        private PayrollPaymentService $payrollPaymentService,
        private AccountService $accountService,
        private PayrollService $payrollService,
        private AccountFilterService $accountFilterService,
        private PaymentMethodService $paymentMethodService,
        private DayBookService $dayBookService,
        private AccountLedgerService $accountLedgerService,
        private AccountingVoucherService $accountingVoucherService,
        private AccountingVoucherDescriptionService $accountingVoucherDescriptionService,
        private AccountingVoucherDescriptionReferenceService $accountingVoucherDescriptionReferenceService,
    ) {
    }

    public function show($id, PayrollPaymentControllerMethodContainersInterface $payrollPaymentControllerMethodContainersInterface)
    {
        $showMethodContainer = $payrollPaymentControllerMethodContainersInterface->showMethodContainer(
            id: $id,
            accountingVoucherService: $this->accountingVoucherService
        );

        extract($showMethodContainer);

        return view('hrm.payroll_payments.show', compact('payment'));
    }

    public function create($payrollId, PayrollPaymentControllerMethodContainersInterface $payrollPaymentControllerMethodContainersInterface)
    {
        $createMethodContainer = $payrollPaymentControllerMethodContainersInterface->createMethodContainer(
            payrollId: $payrollId,
            payrollService: $this->payrollService,
            accountService: $this->accountService,
            accountFilterService: $this->accountFilterService,
            paymentMethodService: $this->paymentMethodService,
        );

        extract($createMethodContainer);

        return view('hrm.payroll_payments.create', compact('accounts', 'expenseAccounts', 'methods', 'payroll'));
    }

    public function store(
        Request $request,
        CodeGenerationServiceInterface $codeGenerator,
        PayrollPaymentControllerMethodContainersInterface $payrollPaymentControllerMethodContainersInterface
    ) {
        $this->payrollPaymentService->storeValidation(request: $request);

        try {
            DB::beginTransaction();

            $storeMethodContainer = $payrollPaymentControllerMethodContainersInterface->storeMethodContainer(
                request: $request,
                payrollPaymentService: $this->payrollPaymentService,
                payrollService: $this->payrollService,
                accountingVoucherService: $this->accountingVoucherService,
                accountingVoucherDescriptionService: $this->accountingVoucherDescriptionService,
                accountingVoucherDescriptionReferenceService: $this->accountingVoucherDescriptionReferenceService,
                dayBookService: $this->dayBookService,
                accountLedgerService: $this->accountLedgerService,
                codeGenerator: $codeGenerator,
            );

            if (isset($storeMethodContainer['pass']) && $storeMethodContainer['pass'] == false) {

                return response()->json(['errorMsg' => $storeMethodContainer['msg']]);
            }

            extract($storeMethodContainer);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        if ($request->action == 'save_and_print') {

            return view('hrm.save_and_print_templates.print_payroll_payment', compact('payment'));
        } else {

            return response()->json(['successMsg' => __('Payroll payment added successfully.')]);
        }
    }

    public function edit($id, PayrollPaymentControllerMethodContainersInterface $payrollPaymentControllerMethodContainersInterface)
    {
        $editMethodContainer = $payrollPaymentControllerMethodContainersInterface->editMethodContainer(
            id: $id,
            accountingVoucherService: $this->accountingVoucherService,
            accountService: $this->accountService,
            accountFilterService: $this->accountFilterService,
            paymentMethodService: $this->paymentMethodService,
        );

        extract($editMethodContainer);

        return view('hrm.payroll_payments.edit', compact('accounts', 'expenseAccounts', 'methods', 'payment'));
    }

    public function update($id, Request $request, PayrollPaymentControllerMethodContainersInterface $payrollPaymentControllerMethodContainersInterface)
    {
        $this->payrollPaymentService->updateValidation(request: $request);

        try {
            DB::beginTransaction();

            $updateMethodContainer = $payrollPaymentControllerMethodContainersInterface->updateMethodContainer(
                id: $id,
                request: $request,
                payrollPaymentService: $this->payrollPaymentService,
                payrollService: $this->payrollService,
                accountingVoucherService: $this->accountingVoucherService,
                accountingVoucherDescriptionService: $this->accountingVoucherDescriptionService,
                accountingVoucherDescriptionReferenceService: $this->accountingVoucherDescriptionReferenceService,
                dayBookService: $this->dayBookService,
                accountLedgerService: $this->accountLedgerService,
            );

            if (isset($updateMethodContainer['pass']) && $updateMethodContainer['pass'] == false) {

                return response()->json(['errorMsg' => $updateMethodContainer['msg']]);
            }

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json(__('Payroll payment updated successfully.'));
    }

    public function delete($id, PayrollPaymentControllerMethodContainersInterface $payrollPaymentControllerMethodContainersInterface)
    {
        try {
            DB::beginTransaction();

            $deleteMethodContainer = $payrollPaymentControllerMethodContainersInterface->deleteMethodContainer(
                id: $id,
                payrollPaymentService: $this->payrollPaymentService,
                payrollService: $this->payrollService,
            );

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json(__('Payroll payment deleted successfully.'));
    }
}
