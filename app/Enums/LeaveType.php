<?php

namespace App\Enums;

enum LeaveType : string
{
    case Sick = 'sick';
    case Leave = 'leave';
    case Personal = 'personal';
    case Other = 'other';
}
