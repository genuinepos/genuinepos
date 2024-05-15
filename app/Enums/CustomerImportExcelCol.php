<?php

namespace App\Enums;

enum CustomerImportExcelCol: int
{
    case Name = 0;
    case Phone = 1;
    case Business = 2;
    case AlternativeNumber = 3;
    case Landline = 4;
    case Email = 5;
    case TaxNumber = 6;
    case OpeningBalance = 7;
    case OpeningBalanceType = 8;
    case CreditLimit = 9;
    case Address = 10;
    case City = 11;
    case State = 12;
    case ZipCode = 13;
    case Country = 14;
    case ShippingAddress = 15;
    case PayTermNumber = 16;
    case PayTerm = 17;
}
