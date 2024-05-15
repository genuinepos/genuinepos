<?php

namespace App\Enums;

enum SubscriptionTransactionType: int
{
    case BuyPlan = 0;
    case AddShop = 1;
    case AddBusiness = 2;
    case ShopRenew = 3;
    case DueRepayment = 4;
}
