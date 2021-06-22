<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PriceGroupController extends Controller
{
    public function index()
    {
        return view('product.price_group.index');
    }
}
