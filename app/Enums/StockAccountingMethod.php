<?php

namespace App\Enums;

enum StockAccountingMethod: int
{
    case FIFO = 1;
    case LIFO = 2;
}
