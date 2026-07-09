<?php

namespace App\Enums;

enum Position : string
{
    case Employee = 'Karyawan';
    case Internship = 'Internship';
    case Onboarding = 'Onboarding';
    case Training = 'Training';
    case Nonactive = 'Nonaktif';
    case SuperAdmin = 'Super Admin';
    case Admin = 'Admin';
}
