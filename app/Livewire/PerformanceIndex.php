<?php

namespace App\Livewire;

use App\Models\User;
use App\Services\KpiService;
use Carbon\Carbon;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;

class PerformanceIndex extends StatsOverviewWidget
{
    protected KpiService $kpiService;
    public ?string $date = null;

    public function boot(KpiService $service): void
    {
        $this->kpiService = $service;
        $this->date = now()->format('Y-m');
    }

    #[On('kpiFilterUpdated')]
    public function updateFilter(?string $date): void
    {
        $this->date = $date;
    }

    protected function getStats(): array
    {
        $user = Auth::user();

        if (! $user) {
            return [];
        }

        $period = $this->date ? Carbon::createFromFormat('Y-m', $this->date) : now();

        $kpi = $this->kpiService->calculate($user->id, $period->year, $period->month);

        return [
            Stat::make('Kehadiran', $kpi['attendance_percentage'] . '%')
                ->description('Target minimum kehadiran: 90%')
                ->color('danger'),
            Stat::make('Kelas Teori', $kpi['theory_class_count'])
                ->description('Total kelas teori')
                ->descriptionIcon(Heroicon::AcademicCap),
            Stat::make('Kelas Daily Talk', $kpi['dt_class_count'])
                ->description('Total kelas daily talk')
                ->descriptionIcon(Heroicon::ChatBubbleLeftRight),
            Stat::make('Closing Rate', $kpi['closing_rate'] . '%')
                ->description('Target minimum: 70%')
                ->descriptionIcon(Heroicon::ChartBar),
            Stat::make('Profit', $kpi['profit'])
                ->description('Total keunguntungan')
                ->descriptionIcon(Heroicon::Banknotes),
            Stat::make('Status', $this->kpiService->getStatus($user, $kpi))
                ->description('Status KPI bulan ini')
                ->descriptionIcon($this->kpiService->isAchieved ? Heroicon::CheckBadge : Heroicon::XCircle)
                ->color($this->kpiService->isAchieved ? 'success' : 'danger'),
        ];
    }
}
