<?php

namespace App\Enums;

enum ProductionStatus: int
{
    case Hold = 0;
    case Final = 1;
}
