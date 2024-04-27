<?php

namespace App\Enums;

enum SubscriptionUpdateType: int
{
    case UpgradePlan = 1;
    case AddShop = 2;
    case AddBusiness = 3;
    case ShopRenew = 4;
    case UpdateExpireDate = 5;
    case UpdatePaymentStatus = 6;
}
