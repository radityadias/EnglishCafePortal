<?php

namespace App\Filament\Resources\AttendanceRecaps\Pages;

use App\Filament\Exports\AttendanceRecapExporter;
use App\Filament\Resources\AttendanceRecaps\AttendanceRecapResource;
use Filament\Actions\CreateAction;
use Filament\Actions\ExportAction;
use Filament\Resources\Pages\ManageRecords;
use Filament\Support\Icons\Heroicon;

class ManageAttendanceRecaps extends ManageRecords
{
    protected static string $resource = AttendanceRecapResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ExportAction::make()
                ->label('Export')
                ->icon(Heroicon::ArrowDownTray)
                ->exporter(AttendanceRecapExporter::class),
            CreateAction::make()
                ->label('Attendance Recap Baru')
        ];
    }
}
