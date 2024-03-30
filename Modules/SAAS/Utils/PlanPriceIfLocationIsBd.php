<?php

namespace Modules\SAAS\Utils;

class PlanPriceIfLocationIsBd
{
    public static function amount(float $amount): float
    {
        $gioInfo = \Modules\SAAS\Utils\GioInfo::getInfo();

        $country = $gioInfo['country'];
        $currencyRateInUsd = $gioInfo['currency_rate'];
        if ($gioInfo['country'] == 'bangladesh') {

            return round(($amount * $currencyRateInUsd), 2);
        }else {

            return round($amount, 2);
        }
    }
}
