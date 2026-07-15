<?php

namespace App\Filament\Widgets;

use App\Models\Announcement;
use Filament\Widgets\Widget;

class AnnouncementWidget extends Widget
{
    protected string $view = 'filament.widgets.announcement-widget';
    protected static ?int $sort = 1;
    protected int|string|array $columnSpan = 'full';
    protected static string $pollingInterval = '60s';

    public function getAnnouncements(): \Illuminate\Database\Eloquent\Collection
    {
        return Announcement::active()
            ->orderByRaw("CASE priority
                WHEN 'danger'  THEN 1
                WHEN 'warning' THEN 2
                WHEN 'info'    THEN 3
                ELSE                4
            END")
            ->orderBy('created_at', 'desc')
            ->get();
    }
}
