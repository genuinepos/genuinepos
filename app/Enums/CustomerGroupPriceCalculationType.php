<?php

namespace App\Enums;

enum CustomerGroupPriceCalculationType: int
{
    case Percentage = 1;
    case SellingPriceGroup = 2;
}
