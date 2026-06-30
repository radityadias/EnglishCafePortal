<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class Announcement extends Widget
{
    protected string $view = 'filament.widgets.announcement';
    protected static ?int $sort = 1;
    protected array | int | string $columnSpan = 'full';
}
