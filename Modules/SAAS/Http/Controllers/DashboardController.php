<?php

namespace Modules\SAAS\Http\Controllers;

use Illuminate\Routing\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        return view('saas::dashboard.index');
    }
}
