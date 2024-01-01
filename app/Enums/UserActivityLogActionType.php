<?php

namespace App\Enums;

enum UserActivityLogActionType: int
{
    case Added = 1;
    case Updated = 2;
    case Deleted = 3;
    case UserLogin = 4;
    case UserLogout = 5;
}
