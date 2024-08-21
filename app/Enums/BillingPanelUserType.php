<?php

namespace App\Enums;

enum BillingPanelUserType: int
{
    case AuthorizeUser = 1;
    case Subscriber = 2;
}
