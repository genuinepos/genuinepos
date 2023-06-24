<?php

namespace Modules\SAAS\Http\Controllers;

use App\Http\Controllers\Controller;

class RegistrationController extends Controller
{
    public function showForm()
    {
        return view('saas::register.form');
    }

    public function store(Request $request)
    {

    }
}
