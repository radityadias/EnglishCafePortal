<?php

namespace App\Filament\Resources\Onboardings\Pages;

use App\Filament\Resources\Onboardings\OnboardingResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageOnboardings extends ManageRecords
{
    protected static string $resource = OnboardingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
            ->label('Onboarding Baru'),
        ];
    }
}
