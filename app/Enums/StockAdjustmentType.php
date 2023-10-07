<?php

namespace App\Enums;

enum StockAdjustmentType: int
{
    case Normal = 1;
    case Abnormal = 2;
}
