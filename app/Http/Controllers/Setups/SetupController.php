<?php

namespace App\Http\Controllers\Setups;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Setups\CurrencyService;
use App\Services\Setups\TimezoneService;
use App\Services\GeneralSettingServiceInterface;

class SetupController extends Controller
{
    public function __construct(
        private CurrencyService $currencyService,
        private TimezoneService $timezoneService,
        private GeneralSettingServiceInterface $generalSettingService
    ) {
        $this->middleware('expireDate');
    }

    function startup() {

        $currencies = $this->currencyService->currencies();
        $timezones = $this->timezoneService->all();

        return view('setups.startup', compact('currencies', 'timezones'));
    }
}
