<?php

namespace App\Filament\Resources\Attendances\Pages;

use App\Filament\Exports\AttendanceExporter;
use App\Filament\Resources\Attendances\AttendanceResource;
use Carbon\Carbon;
use Filament\Actions\CreateAction;
use Filament\Actions\ExportAction;
use Filament\Resources\Pages\ManageRecords;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Builder;

class ManageAttendances extends ManageRecords
{
    protected static string $resource = AttendanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ExportAction::make()
                ->label('Export')
                ->icon(Heroicon::ArrowUpTray)
                ->fileDisk('local')
                ->exporter(AttendanceExporter::class)
                ->modifyQueryUsing(function (Builder $query, array $options): Builder {
                    if (! empty($options['date'])) {
                        $month = Carbon::createFromFormat('Y-m', $options['date']);

                        $query->whereYear('checkin_date', $month->year)
                            ->whereMonth('checkin_date', $month->month);
                    }

                    return $query;
                }),

            CreateAction::make()
                ->icon(Heroicon::Plus)
                ->label('Tambah'),
        ];
    }
}
