<?php

namespace App\Enums;

enum SaleStatus: int
{
    case Final = 1;
    case Draft = 2;
    case Order = 3;
    case Quotation = 4;
    case Hold = 5;
    case Suspended = 6;
}
