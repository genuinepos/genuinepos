<?php

namespace App\Enums;

enum DiscountType: int
{
    case Fixed = 1;
    case Percentage = 2;
}
