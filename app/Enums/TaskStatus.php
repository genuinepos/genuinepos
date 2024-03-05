<?php

namespace App\Enums;

enum TaskStatus: string
{
    case New = 'New';
    case InProgress = 'In-Progress';
    case OnHold = 'On-Hold';
    case Completed = 'Completed';
}
