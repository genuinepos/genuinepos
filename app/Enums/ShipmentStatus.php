<?php

namespace App\Enums;

enum ShipmentStatus: int
{
    case NoStatus = 0;
    case Ordered = 1;
    case Packed = 2;
    case Shipped = 3;
    case Delivered = 4;
    case Cancelled = 5;
    case Completed = 6;
}
