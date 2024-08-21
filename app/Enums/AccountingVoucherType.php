<?php

namespace App\Enums;

enum AccountingVoucherType: int
{
    case Receipt = 1;
    case Payment = 2;
    case Contra = 3;
    case Expense = 4;
    case Income = 5;
    case PayrollPayment = 6;
}
