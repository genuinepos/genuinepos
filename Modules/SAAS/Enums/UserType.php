<?php

namespace Modules\SAAS\Enums;

enum UserType: int
{
    case Admin = 1;
    case Customer = 2;
    case Reseller = 3;
    case Affiliate = 4;
    case Support = 5;
    case Billing = 6;
}
