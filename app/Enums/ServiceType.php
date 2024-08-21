<?php

namespace App\Enums;

enum ServiceType: int
{
    case CarryIn = 1;
    case PickUp = 2;
    case OnSite = 3;
}
