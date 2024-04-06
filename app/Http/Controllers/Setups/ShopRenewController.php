<?php

namespace App\Http\Controllers\Setups;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ShopRenewController extends Controller
{
    public function cart(){

        return view('setups.billing.shop_renew.cart');
    }
}
