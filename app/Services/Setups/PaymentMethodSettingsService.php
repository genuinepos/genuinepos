<?php

namespace App\Services\Setups;

use App\Models\Setups\PaymentMethodSetting;

class PaymentMethodSettingsService
{
    public function paymentMethodSettings(int|null $branchId = null, array|null $with = null): ?object
    {
        $query = PaymentMethodSetting::query();

        if (isset($with)) {

            $query->with($with);
        }

        if (isset($branchId)) {

            $query->where('branch_id', $branchId);
        }

        return $query->first();
    }

    public function addOrUpdatePaymentMethodSettings(object $request) : ?array
    {
        if (isset($request->payment_method_ids)) {

            $index = 0;
            foreach ($request->payment_method_ids as $payment_method_id) {

                $paymentMethodSetting = PaymentMethodSetting::where('branch_id', auth()->user()->branch_id)
                    ->where('payment_method_id', $payment_method_id)
                    ->first();

                $addOrUpdatePaymentMethodSettings = '';

                if ($paymentMethodSetting) {

                    $addOrUpdatePaymentMethodSettings = $paymentMethodSetting;
                } else {

                    $addOrUpdatePaymentMethodSettings = new PaymentMethodSetting();
                }

                $addOrUpdatePaymentMethodSettings->payment_method_id = $payment_method_id;
                $addOrUpdatePaymentMethodSettings->account_id = $request->account_ids[$index];
                $addOrUpdatePaymentMethodSettings->branch_id = auth()->user()->branch_id;
                $addOrUpdatePaymentMethodSettings->save();

                $index++;
            }
        } else {

            return ['pass' => false, 'msg' => __("Failed! Payment method is empty.")];
        }

        return ['pass' => true];
    }
}
