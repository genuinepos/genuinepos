<?php

namespace App\Http\Controllers\Setups;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Modules\SAAS\Interfaces\CouponServiceInterface;

class CheckCouponCodeController extends Controller
{
    public function __construct(
        private CouponServiceInterface $couponServiceInterface,
    ) {
    }

    public function checkCouponCode(Request $request)
    {
        DB::statement('use ' . env('DB_DATABASE'));
        $checkCouponCode = $this->couponServiceInterface->checkCouponCode(request: $request);
        DB::reconnect();

        if (isset($checkCouponCode['pass']) && $checkCouponCode['pass'] == false) {

            return response()->json(['errorMsg' => $checkCouponCode['msg']]);
        }

        return $checkCouponCode;
    }
}
