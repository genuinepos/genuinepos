<?php

namespace App\Enums;

enum SubscriptionTransactionType: int
{
    case BuyPlan = 0;
    case ShopIncrease = 1;
    case IncludeBusiness = 2;
    case ShopRenew = 3;
    case DueRepayment = 4;
}
