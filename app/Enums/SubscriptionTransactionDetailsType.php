<?php

namespace App\Enums;

enum SubscriptionTransactionDetailsType: string
{
    case UpgradePlanFromTrial = 'upgrade_plan_from_trial';
    case DirectBuyPlan = 'direct_buy_plan';
    case UpgradePlanFromRealPlan = 'upgrade_plan_from_real_plan';
    case AddShop = 'add_shop';
}
