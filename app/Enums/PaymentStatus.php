<?php

namespace App\Enums;

enum PaymentStatus: int
{
    case Paid = 1;
    case Partial = 2;
    case Due = 3;
}
