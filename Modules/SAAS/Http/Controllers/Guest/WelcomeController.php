<?php

namespace Modules\SAAS\Http\Controllers\Guest;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class WelcomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function __invoke()
    {
        // if (Auth::check()) {
        //     return \redirect(route('saas.dashboard'));
        // }
        return view('saas::guest.welcome-page');
    }
}
