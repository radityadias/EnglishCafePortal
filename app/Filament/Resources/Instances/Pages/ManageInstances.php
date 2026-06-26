<?php

namespace App\Filament\Resources\Instances\Pages;

use App\Filament\Resources\Instances\InstanceResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageInstances extends ManageRecords
{
    protected static string $resource = InstanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
            ->label('Instance Baru'),
        ];
    }
}
