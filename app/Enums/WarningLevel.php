<?php

namespace App\Enums;

enum WarningLevel: string
{
    case None = 'none';
    case Low = 'low';
    case Medium = 'medium';
    case High = 'high';

    public function getLabel(): string
    {
        return match ($this) {
            self::None => 'None',
            self::Low => 'Low',
            self::Medium => 'Medium',
            self::High => 'High',
        };
    }

}
