<?php

namespace Modules\SAAS\Utils;

class ExpireDateAllocation
{
    public static function getExpireDate(string $period, int $periodCount, ?string $startDate = null) : string
    {
        $today = new \DateTime();
        $_startDate = isset($startDate) ? new \DateTime($startDate) : new \DateTime();
        $lastDate = '';
        if ($period == 'day') {

            $lastDate = $_startDate->modify('+' . $periodCount . ' days');
            $lastDate = $_startDate->modify('+1 days');
        } elseif ($period == 'month') {

            $lastDate = $_startDate->modify('+' . $periodCount . ' months');
            $lastDate = $_startDate->modify('+1 days');
        } elseif ($period == 'year') {

            $lastDate = $_startDate->modify('+' . $periodCount . ' years');
            $lastDate = $_startDate->modify('+1 days');
        }

        // Format the date
        return $lastDate->format('Y-m-d');
    }
}
