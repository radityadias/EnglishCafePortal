<?php

namespace App\Livewire;

use App\Models\Appointment;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class ProfitChart extends ChartWidget
{
    protected ?string $heading = 'Profit';

    protected function getData(): array
    {
        return [
            'datasets' => [
                [
                    'label' => 'Profit ' . now()->year,
                    'data' => $this->getTotalProfitThisYear(),
                    'borderColor' => '#3b82f6',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.5)',
                    'fill' => true,
                ],
                [
                    'label' => 'Profit ' . now()->year - 1,
                    'data' => $this->getTotalProfitLastYear(),
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
        return 'bar';
    }

    private function getTotalProfitThisYear(): array
    {
        $rows = Appointment::select(
            DB::raw('EXTRACT(MONTH FROM created_at) as month'),
            DB::raw('SUM(payment_amount) as total')
        )
            ->whereYear('created_at', now()->year)
            ->groupBy(DB::raw('EXTRACT(MONTH FROM created_at)'))
            ->get()
            ->keyBy(fn ($row) => (int) $row->month);

        $data = [];

        for ($month = 1; $month <= 12; $month++) {
            $row = $rows->get($month);
            $data[] = $row ? (float) $row->total : 0.0;
        }

        return $data;
    }

    private function getTotalProfitLastYear(): array
    {
        $rows = Appointment::select(
            DB::raw('EXTRACT(MONTH FROM created_at) as month'),
            DB::raw('SUM(payment_amount) as total')
        )
            ->whereYear('created_at', now()->year - 1)
            ->groupBy(DB::raw('EXTRACT(MONTH FROM created_at)'))
            ->get()
            ->keyBy(fn ($row) => (int) $row->month);

        $data = [];

        for ($month = 1; $month <= 12; $month++) {
            $row = $rows->get($month);
            $data[] = $row ? (float) $row->total : 0.0;
        }

        return $data;
    }
}
