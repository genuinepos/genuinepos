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
    case SaleTax = 16;
    case PurchaseTax = 17;
    case SalesReturnTax = 18;
    case PurchaseReturnTax = 19;
}
