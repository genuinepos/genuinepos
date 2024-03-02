<?php

namespace App\Enums;

enum SubscriptionTransactionType: int
{
    case Installation = 0;
    case UpgradePlan = 1;
    case ShopIncrease = 2;
    case ShopRenew = 3;
    case DueRepayment = 4;
}
