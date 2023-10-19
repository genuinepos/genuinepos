<?php

namespace App\Enums;

enum ProductLedgerVoucherType: int
{
    case OpeningStock = 0;
    case Sales = 1;
    case SalesReturn = 2;
    case Purchase = 3;
    case PurchaseReturn = 4;
    case StockAdjustment = 5;
    case Production = 6;
    case TransferStock = 7;
    case ReceiveStock = 8;
}
