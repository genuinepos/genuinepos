<?php

namespace Modules\SAAS\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\SAAS\Entities\Domain;

class DomainAvailabilityController extends Controller
{
    public function checkAvailability(Request $request)
    {
        $request->validate(['domain' => 'required|string|max:191']);
        $isAvailable = Domain::isAvailable($request->domain);

        return response()->json(['isAvailable' => $isAvailable]);
    }
}
