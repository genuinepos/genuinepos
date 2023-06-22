<?php

namespace Modules\SAAS\Http\Controllers\SAAS;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RegistrationController extends Controller
{
    public function showForm()
    {
        return view('saas::register.form');
    }
}
