<?php

namespace App\Enums;

enum SupplierImportExcelCol: int
{
    case Name = 0;
    case Phone = 1;
    case Business = 2;
    case AlternativeNumber = 3;
    case Landline = 4;
    case Email = 5;
    case TaxNumber = 6;
    case Address = 7;
    case City = 8;
    case State = 9;
    case ZipCode = 10;
    case Country = 11;
    case PayTermNumber = 12;
    case PayTerm = 13;
}
