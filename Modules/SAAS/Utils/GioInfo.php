<?php

namespace Modules\SAAS\Utils;

class GioInfo
{
    public static function getInfo(): mixed
    {
        // $ip = $_SERVER['REMOTE_ADDR'];
        // $ip = '102.38.238.0';
        // // $ip = '103.109.52.0';
        // // $ip = '103.109.236.0';
        // $location = unserialize(file_get_contents('http://www.geoplugin.net/php.gp?ip=' . $ip, false));

        // return [
        //     'country' => isset($location) ? strtolower($location['geoplugin_countryName']) : 'USA',
        //     'currency_rate' => $location ? strtolower($location['geoplugin_currencyConverter']) : '0.00',
        // ];

        return [
            'country' => 'USA',
            'currency_rate' => '0.00',
        ];
    }
}
