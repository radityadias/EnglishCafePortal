<?php

namespace App\Enums;

enum WorkType : string
{
    case Fixed = "fixed";
    case Regular = 'regular';
    case Flexible = 'flexible';
}
