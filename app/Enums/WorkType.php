<?php

namespace App\Enums;

enum WorkType : string
{
    case Fixed = "fixed";
    case Regular = 'regular';
    case Flexible = 'flexible';

    public function getLabel(): string
    {
        return match ($this) {
            self::Fixed => "Tetap",
            self::Regular => "8 Jam",
            self::Flexible => "Fleksibel",
        };
    }
}
