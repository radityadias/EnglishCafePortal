<?php

namespace App\Filament\Exports;

use App\Models\Attendance;
use Carbon\Carbon;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Number;

class AttendanceExporter extends Exporter
{
    protected static ?string $model = Attendance::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('user.name'),
            ExportColumn::make('status'),
            ExportColumn::make('checkin_date'),
            ExportColumn::make('checkin_time'),
            ExportColumn::make('checkout_time'),
        ];
    }

    public static function getOptionsFormComponents(): array
    {
        return [
            DatePicker::make('date')
                ->label('Bulan & Tahun')
                ->native()
                ->extraInputAttributes(['type' => 'month'])
                ->format('Y-m'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your attendance export has completed and ' . Number::format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
