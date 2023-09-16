<?php

namespace App\Enums;

enum DayBookVoucherType: int
{
    case Sales = 1;
    case SalesOrder = 2;
    case SalesReturn = 3;
    case Purchase = 4;
    case PurchaseOrder = 5;
    case PurchaseReturn = 6;
    case StockAdjustment = 7;
    case Receipt = 8;
    case Payment = 9;
    case Contra = 10;
    case Expense = 11;
    case Incomes = 12;
}
