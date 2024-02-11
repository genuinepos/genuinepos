<?php

namespace App\Enums;

enum SubscriptionPaymentStatus: int
{
    case Due = 0;
    case Paid = 1;
}
