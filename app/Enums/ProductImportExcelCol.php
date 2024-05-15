<?php

namespace App\Enums;

enum ProductImportExcelCol: int
{
    case Name = 0;
    case ProductCode = 1;
    case UnitCode = 2;
    case CategoryCode = 3;
    case SubcategoryCode = 4;
    case BrandCode = 5;
    case WarrantyCode = 6;
    case StockType = 7;
    case AlertQty = 8;
    case TaxPercent = 9;
    case TaxType = 10;
    case UnitCostExcTax = 11;
    case UnitCostIncTax = 12;
    case SellingPrice = 13;
    case EnableIMEIorSerialNo = 14;
    case IsForSale = 15;
    case EnableBatchNoOrSerialNo = 16;
    case OpeningStock = 17;
}
