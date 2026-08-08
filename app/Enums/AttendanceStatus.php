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

    public function getLabel(): string
    {
        return match ($this) {
            self::Attend => "On Time",
            self::Late => "Terlambat",
            self::Absent => "Tidak Hadir",
            self::Incomplete => "Tidak Sah",
            self::Leave => "Izin",
            self::CheckIn => "Masuk",
            self::CheckOut => "Keluar",
        };
    }
}
