<?php

namespace Modules\SAAS\Utils;

class PlanPriceCurrencySymbol
{
    public static function currencySymbol(): string
    {
        $gioInfo = \Modules\SAAS\Utils\GioInfo::getInfo();
        $country = $gioInfo['country'];

        $currency = '$';
        if ($country == 'bangladesh') {

            $currency = 'TK.';
        }

        return $currency;
    }
}
