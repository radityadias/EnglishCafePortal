<?php

namespace App\Filament\Resources\AttendanceRecaps\Pages;

use App\Filament\Resources\AttendanceRecaps\AttendanceRecapResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageAttendanceRecaps extends ManageRecords
{
    protected static string $resource = AttendanceRecapResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
            ->label('Attendance Recap Baru'),
        ];
    }
}
