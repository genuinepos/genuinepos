<?php

namespace App\Enums;

enum RoleType: int
{
    case SuperAdmin = 1;
    case Admin = 2;
    case Other = 3;
}
