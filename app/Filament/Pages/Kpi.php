<?php

namespace App\Filament\Pages;

use App\Livewire\ClosingRateChart;
use App\Livewire\PerformanceIndex;
use App\Livewire\ProfitChart;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Auth;
use UnitEnum;

class Kpi extends Page
{
    public ?string $date = null;

    protected string $view = 'filament.pages.kpi';
    protected static ?string $title = 'KPI';
    protected static string | BackedEnum | null $navigationIcon = Heroicon::ChartPie;
    protected static string | UnitEnum | null $navigationGroup = 'Akun';

    public function mount(): void
    {
        $this->date = now()->format('Y-m');
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('filter')
                ->icon(Heroicon::CalendarDays)
                ->label($this->date ?? 'Filter Waktu')
                ->modalHeading("Filter Waktu")
                ->modalWidth('sm')
                ->schema([
                    DatePicker::make('date')
                        ->label('Waktu')
                        ->native()
                        ->extraInputAttributes(['type' => 'month'])
                        ->format('Y-m')
                        ->default(fn () => $this->date)
                        ->maxWidth('sm')
                        ->required(),
                ])
                ->action(function (array $data): void {
                    $this->date = $data['date'];
                    $this->dispatch('kpiFilterUpdated', date: $this->date);
                }),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            PerformanceIndex::class,
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [
            ClosingRateChart::class,
            ProfitChart::class,
        ];
    }

    public static function canAccess(): bool
    {
        return Auth::user()?->canAccessKpiFeatures() ?? false;
    }
}
