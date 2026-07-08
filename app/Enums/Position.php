<?php

namespace App\Enums;

enum Position : string
{
    case Employee = 'Karyawan';
    case Internship = 'Internship';
    case Onboarding = 'Onboarding';
    case Training = 'Training';
    case Nonactive = 'Nonaktif';
    case Admin = 'Admin';
    case SuperAdmin = 'Super Admin';
}
