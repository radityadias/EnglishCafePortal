<?php

namespace App\Enums;

enum AttendanceStatus: string
{
    case Attend = 'attend';
    case Late = 'late';
    case Absent = 'absent';
    case Incomplete = 'incomplete';
    case Leave = 'leave';
    case CheckIn = 'checkin';
    case CheckOut = 'checkout';
}
