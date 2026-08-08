<?php

namespace App\Livewire;

use App\Models\Appointment;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class ClosingRateChart extends ChartWidget
{
    protected ?string $heading = 'Closing Rate';

    protected function getData(): array
    {
        return [
            'datasets' => [
                [
                    'label' => 'Closing Rate ' . now()->year . '(%)',
                    'data' => $this->getClosingRateThisYear(),
                    'borderColor' => '#22c55e',
                    'backgroundColor' => 'rgba(34, 197, 94, 0.2)',
                    'fill' => true,
                ],
                [
                    'label' => 'Closing Rate ' . now()->year - 1 . '(%)',
                    'data' => $this->getClosingRateLastYear(),
                    'fill' => true,
                ]
            ],
            'labels' => [
                'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec',
            ],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    private function getClosingRateThisYear(): array
    {
        $rows = Appointment::select(
            DB::raw('EXTRACT(MONTH FROM created_at) as month'),
            DB::raw('COUNT(*) as total'),
            DB::raw('SUM(CASE WHEN is_success = true THEN 1 ELSE 0 END) as successful')
        )
            ->whereYear('created_at', now()->year)
            ->groupBy(DB::raw('EXTRACT(MONTH FROM created_at)'))
            ->get()
            ->keyBy(fn ($row) => (int) $row->month);

        $data = [];

        for ($month = 1; $month <= 12; $month++) {
            $row = $rows->get($month);

            $data[] = $row && $row->total > 0
                ? round(($row->successful / $row->total) * 100, 1)
                : 0.0;
        }

        return $data;
    }

    private function getClosingRateLastYear(): array
    {
        $rows = Appointment::select(
            DB::raw('EXTRACT(MONTH FROM created_at) as month'),
            DB::raw('COUNT(*) as total'),
            DB::raw('SUM(CASE WHEN is_success = true THEN 1 ELSE 0 END) as successful')
        )
            ->whereYear('created_at', now()->year - 1)
            ->groupBy(DB::raw('EXTRACT(MONTH FROM created_at)'))
            ->get()
            ->keyBy(fn ($row) => (int) $row->month);

        $data = [];

        for ($month = 1; $month <= 12; $month++) {
            $row = $rows->get($month);

            $data[] = $row && $row->total > 0
                ? round(($row->successful / $row->total) * 100, 1)
                : 0.0;
        }

        return $data;
    }


}
