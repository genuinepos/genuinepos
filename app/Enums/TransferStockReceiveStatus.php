<?php

namespace App\Enums;

enum TransferStockReceiveStatus: int
{
    case Pending = 0;
    case Partial = 1;
    case Completed = 2;
}
