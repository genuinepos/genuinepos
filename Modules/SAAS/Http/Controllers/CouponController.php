<?php

namespace Modules\SAAS\Http\Controllers;

use Illuminate\Http\Request;
use Modules\SAAS\Entities\Coupon;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use Modules\SAAS\Http\Requests\CouponStoreRequest;
use Modules\SAAS\Http\Requests\CouponUpdateRequest;
use Modules\SAAS\Interfaces\CouponServiceInterface;

class CouponController extends Controller
{
    public function __construct(
        private CouponServiceInterface $couponServiceInterface,
    ) {
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {

            return $this->couponServiceInterface->couponsTable();
        }

        return view('saas::coupons.index');
    }

    public function create()
    {
        return view('saas::coupons.create');
    }

    public function store(CouponStoreRequest $request)
    {
        $this->couponServiceInterface->addCoupon(request: $request);
        return redirect()->route('saas.coupons.index')->with('success', __('Coupon has been created successfully'));
    }

    public function edit($id)
    {
        $coupon = $this->couponServiceInterface->singleCouponById(id: $id);
        return view('saas::coupons.edit', compact('coupon'));
    }

    public function update($id, CouponUpdateRequest $request)
    {
        $this->couponServiceInterface->updateCoupon(request: $request, id: $id);
        return redirect()->route('saas.coupons.index')->with('success', __('Coupon has been updated successfully'));
    }

    public function delete($id)
    {
        $deleteCoupon = $this->couponServiceInterface->deleteCoupon(id: $id);

        if ($deleteCoupon['pass'] == false) {

            return redirect()->route('saas.coupons.index')->with('error', $deleteCoupon['msg']);
        }

        return redirect()->route('saas.coupons.index')->with('success', __('Coupon has been deleted successfully'));
    }

    public function checkCouponCode(Request $request)
    {
        $checkCouponCode = $this->couponServiceInterface->checkCouponCode(request: $request);

        if (isset($checkCouponCode['pass']) && $checkCouponCode['pass'] == false) {

            return response()->json(['errorMsg' => $checkCouponCode['msg']]);
        }

        return $checkCouponCode;
    }
}
