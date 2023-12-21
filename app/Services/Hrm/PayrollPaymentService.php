<?php

namespace App\Services\Hrm;

use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class PayrollPaymentService
{
    public function restrictions(object $request): array
    {
        if ($request->paying_amount < 1) {

            return ['pass' => false, 'msg' => __('Pay amount must be greater then 0')];
        }

        return ['pass' => true];
    }
}
