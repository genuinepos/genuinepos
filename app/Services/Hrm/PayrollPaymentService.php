<?php

namespace App\Services\Hrm;

use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Accounts\AccountingVoucher;

class PayrollPaymentService
{
    public function restrictions(object $request): array
    {
        if ($request->paying_amount < 1) {

            return ['pass' => false, 'msg' => __('Pay amount must be greater then 0')];
        }

        return ['pass' => true];
    }

    public function deletePayrollPayment(int $id): object
    {
        $payment = $this->singlePayrollPayment(
            id: $id,
            with: ['payrollRef'],
        );

        if (isset($payment)) {

            $payment->delete();
        }

        return $payment;
    }

    public function singlePayrollPayment(int $id, array $with = null): ?object
    {
        $query = AccountingVoucher::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query->where('id', $id)->first();
    }
}
