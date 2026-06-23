<?php

namespace App\Filament\Resources\Internships\Pages;

use App\Filament\Resources\Internships\InternshipResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewInternship extends ViewRecord
{
    protected static string $resource = InternshipResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
