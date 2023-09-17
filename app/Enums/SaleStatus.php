<?php

namespace App\Enums;

enum SaleStatus: int
{
    case Final = 1;
    case Draft = 2;
    case Ordered = 3;
    case Quotation = 4;
}
