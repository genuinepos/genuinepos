<?php

namespace App\Http\Controllers\Startup;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\Startup\StartupFinishRequest;
use App\Interfaces\Startup\StartupControllerMethodContainerInterface;

class StartupController extends Controller
{
    public function startupFrom(StartupControllerMethodContainerInterface $startupControllerMethodContainerInterface)
    {
        $startupFromContainer = $startupControllerMethodContainerInterface->startupFromContainer();
        if (isset($startupFromContainer['pass']) && $startupFromContainer['pass'] == false) {

            return redirect()->back();
        }

        extract($startupFromContainer);

        if ($direction == 'business_and_branch') {

            return view('startup.startup_form_with_business_and_branch', compact('currencies', 'timezones', 'roles'));
        } else if ($direction == 'branch') {

            return view('startup.startup_form_with_branch', compact('currencies', 'timezones', 'roles'));
        } else if ($direction == 'business') {

            return view('startup.startup_form_with_business', compact('currencies', 'timezones'));
        }
    }

    public function finish(StartupFinishRequest $request, StartupControllerMethodContainerInterface $startupControllerMethodContainerInterface)
    {
        try {
            DB::beginTransaction();

            $startupControllerMethodContainerInterface->finishMethodContainer(request: $request);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return route('dashboard.index');
    }
}
