<?php

namespace Modules\SAAS\Utils;

class ExpireDateAllocation
{
    public static function getExpireDate(string $period, int $periodCount) : string
    {
        $today = new \DateTime();
        $lastDate = '';
        if ($period == 'day') {

            $lastDate = $today->modify('+' . $periodCount . ' days');
            $lastDate = $today->modify('+1 days');
        } elseif ($period == 'month') {

            $lastDate = $today->modify('+' . $periodCount . ' months');
            $lastDate = $today->modify('+1 days');
        } elseif ($period == 'year') {

            $lastDate = $today->modify('+' . $periodCount . ' years');
            $lastDate = $today->modify('+1 days');
        }

        // Format the date
        return $lastDate->format('Y-m-d');
    }
}
