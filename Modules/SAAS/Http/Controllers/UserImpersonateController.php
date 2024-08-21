<?php

namespace Modules\SAAS\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Stancl\Tenancy\Features\UserImpersonation;

class UserImpersonateController extends Controller
{
    public function impersonate(Request $request, $token)
    {
        return UserImpersonation::makeResponse($token);
    }
}
