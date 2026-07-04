<?php

namespace App;

enum AttendanceStatus: string
{
    case Attend = 'attend';
    case Late = 'late';
    case Absent = 'absent';
}
