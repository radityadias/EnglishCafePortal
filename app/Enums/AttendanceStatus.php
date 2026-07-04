<?php

namespace App\Enums;

enum AttendanceStatus: string
{
    case Attend = 'attend';
    case Late = 'late';
    case Absent = 'absent';
}
