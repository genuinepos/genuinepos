<?php

namespace App\Enums;

enum PlanPricePeriod: string
{
    case Months = 'month';
    case Years = 'year';
    case Lifetime = 'lifetime';
}
