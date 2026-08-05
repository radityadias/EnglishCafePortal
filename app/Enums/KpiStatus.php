<?php

namespace App\Enums;

enum KpiStatus: string
{
    case Achieved = 'achieved';
    case NotAchieved = 'not_achieved';

    public function getLabel(): string
    {
        return match ($this) {
            self::Achieved => 'Achieved',
            self::NotAchieved => 'Not Achieved',
        };
    }
}
