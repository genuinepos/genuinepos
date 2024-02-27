<?php

namespace App\Http\Controllers\Setups;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ChangeBusinessOrBranchLocationController extends Controller
{
    public function index()  {

        return view('setups.choose_shop_or_business.index');
    }
}
