<?php

namespace App\Enums;

enum UserType: int
{
    case User = 1;
    case Employee = 2;
    case Both = 3;
}
