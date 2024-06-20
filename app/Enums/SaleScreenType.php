<?php

namespace App\Enums;

enum SaleScreenType: int
{
    case AddSale = 1;
    case PosSale = 2;
    case ServicePosSale = 3;
}
