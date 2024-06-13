<?php

namespace App\Enums;

enum AccountCreateAndEditType: int
{
    case Capitals = 1;
    case DutiesAndTaxes = 2;
    case Others = 3;
    case All = 4;
}
