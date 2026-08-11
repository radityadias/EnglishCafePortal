<?php

namespace App\Filament\Widgets;

use App\Enums\AttendanceStatus;
use App\Models\Attendance;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AttendanceSummaryStats extends StatsOverviewWidget
{
    protected static ?int $sort = 2;

    public function getColumns(): int|array|null
    {
        return [
            'default' => 2,
            'md' => 4,
        ];
    }

    protected function getStats(): array
    {
        $summary = $this->getMonthlySummary();

        return [
            Stat::make('Kehardiran bulan ini', $summary->attended)
                ->description('Minimal kehadiran: ' . $this->getWeekDays())
                ->color('success'),
            Stat::make('Terlambat', $summary->late)
                ->color('warning'),
            Stat::make('Tidak hadir', $summary->absent)
                ->description('Maksimal ketidakhadiran: ' . 3)
                ->color('info'),
            Stat::make('Total Peringatan', $summary->warnings)
                ->color('danger'),
        ];
    }

    private function getMonthlySummary(): object
    {
        return Attendance::where('user_id', auth()->id())
            ->whereMonth('checkin_date', now()->month)
            ->whereYear('checkin_date', now()->year)
            ->selectRaw("
               SUM(CASE WHEN status IN (?, ?) THEN 1 ELSE 0 END) AS attended,
               SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) AS late,
               SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) AS absent,
               COUNT(CASE WHEN warning_level IS NOT NULL THEN 1 END) AS warnings
            ", [
                AttendanceStatus::Attend->value,
                AttendanceStatus::Late->value,
                AttendanceStatus::Late->value,
                AttendanceStatus::Absent->value,
            ])
            ->first();
    }

    private function getWeekDays(): int
    {
        $date = now();
        $period = CarbonPeriod::create($date->copy()->startOfMonth(), $date->copy()->endOfMonth());

        return $period->filter(fn (Carbon $date) => ! $date->isSunday())->count();
    }
}
