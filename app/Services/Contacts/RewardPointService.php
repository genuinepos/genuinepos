<?php

namespace App\Services\Contacts;

class RewardPointService
{
    public function calculateCustomerPoint($generalSettings, $totalAmount)
    {
        $enable_cus_point = $generalSettings['reward_point_settings__enable_cus_point'];

        (int) $amount_for_unit_rp = $generalSettings['reward_point_settings__amount_for_unit_rp'];

        (int) $min_order_total_for_rp = $generalSettings['reward_point_settings__min_order_total_for_rp'];

        (int) $max_rp_per_order = $generalSettings['reward_point_settings__max_rp_per_order'];

        if ($enable_cus_point == '1') {

            if ($min_order_total_for_rp && $totalAmount >= $min_order_total_for_rp) {

                if ($amount_for_unit_rp != 0) {

                    $calc_point = $totalAmount / $amount_for_unit_rp;
                    $__net_point = (int) $calc_point;

                    if ($max_rp_per_order && $__net_point > $max_rp_per_order) {

                        return $max_rp_per_order;
                    } else {

                        return $__net_point;
                    }
                } else {

                    return 0;
                }
            } else {

                return 0;
            }
        } else {

            return 0;
        }
    }
}
