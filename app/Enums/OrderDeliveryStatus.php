<?php

namespace App\Enums;

enum OrderDeliveryStatus: int
{
    case Pending = 0;
    case Partial = 1;
    case Completed = 2;
}
