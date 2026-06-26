<?php

namespace App\Filament\Resources\WorkingTimes\Pages;

use App\Filament\Resources\WorkingTimes\WorkingTimeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageWorkingTimes extends ManageRecords
{
    protected static string $resource = WorkingTimeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
            ->label('Working Time Baru'),
        ];
    }
}
