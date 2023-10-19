<?php

namespace App\Enums;

enum TransferStockType: int
{
    case WarehouseToBranch = 1;
    case BranchToWarehouse = 2;
    case BranchToBranch = 3;
    case WarehouseToWarehouse = 4;
}
