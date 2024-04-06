<?php

namespace Modules\SAAS\Services;

use App\Enums\BooleanType;
use Modules\SAAS\Entities\Coupon;
use Yajra\DataTables\Facades\DataTables;
use Modules\SAAS\Interfaces\CouponServiceInterface;

class CouponService implements CouponServiceInterface
{
    function couponsTable()
    {
        $coupons = $this->coupons()->orderBy('created_at', 'desc');

        return DataTables::of($coupons)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {

                $html = '<div class="dropdown table-dropdown">';

                if (auth()->user()->can('coupons_update')) {

                    $html .= '<a href="' . route('saas.coupons.edit', $row->id) . '" class="px-2 edit-btn btn btn-primary btn-sm text-white" title="Edit"><span class="fas fa-edit pe-1"></span>' . __("Edit") . '</a>';
                }

                if (auth()->user()->can('coupons_destroy')) {

                    $html .= '<a href="' . route('saas.coupons.destroy', $row->id) . '" class="px-2 trash-btn btn btn-danger btn-sm text-white ms-2" id="delete" title="Delete"><span class="fas fa-trash pe-1"></span>' . __("Delete") . '</a>';
                }

                $html .= '</div>';

                return $html;
            })->make(true);
    }

    public function addCoupon(object $request): void
    {
        $data = [
            'code' => $request->code,
            'start_date' => date('Y-m-d', strtotime($request->start_date)),
            'end_date' => date('Y-m-d', strtotime($request->end_date)),
            'percent' => $request->percent,
            'is_minimum_purchase' => $request->is_minimum_purchase,
            'minimum_purchase_amount' => isset($request->is_minimum_purchase) ? $request->minimum_purchase_amount : 0,
            'is_maximum_usage' => $request->is_maximum_usage,
            'no_of_usage' => $request->is_maximum_usage == BooleanType::True->value ? $request->no_of_usage : 0,
        ];

        Coupon::create($data);
    }

    public function updateCoupon(object $request, int $id): void
    {
        $coupon = $this->singleCouponById(id: $id);

        $data = [
            'code' => $request->code,
            'start_date' => date('Y-m-d', strtotime($request->start_date)),
            'end_date' => date('Y-m-d', strtotime($request->end_date)),
            'percent' => $request->percent,
            'is_minimum_purchase' => $request->is_minimum_purchase,
            'minimum_purchase_amount' => $request->is_minimum_purchase == BooleanType::True->value ? $request->minimum_purchase_amount : 0,
            'is_maximum_usage' => $request->is_maximum_usage,
            'no_of_usage' => $request->is_maximum_usage == BooleanType::True->value ? $request->no_of_usage : 0,
        ];

        $coupon->update($data);
    }

    public function increaseCouponNumberOfUsed(string $code): void
    {
        $coupon = $this->singleCouponByCode(code: $code);

        if (isset($coupon)) {
            $coupon->no_of_used += 1;
            $coupon->save();
        }
    }

    public function deleteCoupon(int $id): array
    {
        $deleteCoupon = $this->singleCouponById(id: $id);

        if (isset($deleteCoupon)) {

            if ($deleteCoupon->no_of_used > 0) {

                return ['pass' => false, 'msg' => __('Coupon has already been used, can not delete this coupon')];
            }

            $deleteCoupon->delete();
        }

        return ['pass' => true];
    }

    public function checkCouponCode(object $request): array|object
    {
        if (empty($request->coupon_code)) {

            return ['pass' => false, 'msg' => __('Please enter a valid coupon code')];
        }

        $coupon = $this->singleCouponByCode(code: $request->coupon_code);
        if (!isset($coupon)) {

            return ['pass' => false, 'msg' => __('Coupon code not found.')];
        }

        if (date('Y-m-d') < $coupon->start_date) {

            return ['pass' => false, 'msg' => __("Coupon is not yet to be valid.")];
        }

        if ($coupon->end_date < date('Y-m-d')) {

            return ['pass' => false, 'msg' => __("Coupon is expired.")];
        }

        if ($coupon->is_minimum_purchase) {

            $totalPayableInUsd = \Modules\SAAS\Utils\AmountInUsdIfLocationIsBd::amountInUsd($request->total_payable);
            if ($totalPayableInUsd < $coupon->minimum_purchase_amount) {

                $minimumPurchaseAmount = \Modules\SAAS\Utils\PlanPriceIfLocationIsBd::amount($coupon->minimum_purchase_amount);
                return ['pass' => false, 'msg' => __("Coupon can not be applied, Cause minimum purchase amount is ${minimumPurchaseAmount} for applying this coupon.")];
            }
        }

        if ($coupon->is_maximum_usage) {

            if ($coupon->no_of_used >= $coupon->no_of_usage) {

                $minimumPurchaseAmount = \Modules\SAAS\Utils\PlanPriceIfLocationIsBd::amount($coupon->minimum_purchase_amount);
                return ['pass' => false, 'msg' => __("Coupon can not be applied, Coupon is expired.")];
            }
        }

        return $coupon;
    }

    public function singleCouponByCode(?string $code, ?array $with = null): ?object
    {
        $query = Coupon::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query->where('code', $code)->first();
    }

    public function singleCouponById(int $id, array $with = null): ?object
    {
        $query = Coupon::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query->where('id', $id)->first();
    }

    public function coupons(array $with = null): object
    {
        $query = Coupon::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query;
    }
}
