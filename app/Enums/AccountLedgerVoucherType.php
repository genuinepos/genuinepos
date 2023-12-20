<?php

namespace App\Enums;

enum AccountLedgerVoucherType: int
{
    case OpeningBalance = 0;
    case Sales = 1;
    case SalesReturn = 2;
    case Purchase = 3;
    case PurchaseReturn = 4;
    case Expense = 5;
    case StockAdjustment = 7;
    case Receipt = 8;
    case Payment = 9;
    case Contra = 12;
    case Journal = 13;
    case Incomes = 15;
    case SaleProductTax = 16;
    case PurchaseProductTax = 17;
    case SalesReturnProductTax = 18;
    case PurchaseReturnProductTax = 19;
    case Exchange = 20;
    case PayrollPayment = 21;
}
