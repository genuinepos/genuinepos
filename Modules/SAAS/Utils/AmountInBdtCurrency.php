<?php

namespace Modules\SAAS\Utils;

class AmountInBdtCurrency
{
    public static function amountInBdt(float $amount): float
    {
        $gioInfo = \Modules\SAAS\Utils\GioInfo::getInfo();

        $country = $gioInfo['country'];
        $currencyRateInUsd = $gioInfo['currency_rate'];
        if ($gioInfo['country'] == 'bangladesh') {

            return round(($amount * $currencyRateInUsd), 0);
        } else {

            return round($amount, 0);
        }
    }
}
