<?php

namespace Modules\SAAS\Utils;

class GioInfo
{
    public static function getInfo() : ?array {

        // $ip = $_SERVER['REMOTE_ADDR'];
        $ip = '102.38.238.0';
        $location = unserialize(file_get_contents('http://www.geoplugin.net/php.gp?ip=' . $ip));
        return [
            'country' => strtolower($location['geoplugin_countryName']),
            'currency_rate' => strtolower($location['geoplugin_currencyConverter']),
        ];
    }
}
