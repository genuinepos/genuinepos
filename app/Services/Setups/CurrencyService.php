<?php

namespace App\Services\Setups;

use App\Models\Setups\Currency;

class CurrencyService
{
    public function currencies() {

        return Currency::orderBy('country')->get();
    }
}
