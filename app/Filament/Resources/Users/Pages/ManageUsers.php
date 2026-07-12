<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Exports\UserExporter;
use App\Filament\Imports\UserImporter;
use App\Filament\Resources\Users\UserResource;
use Filament\Actions\CreateAction;
use Filament\Actions\ImportAction;
use Filament\Resources\Pages\ManageRecords;
use Filament\Support\Icons\Heroicon;
use Filament\Actions\ExportAction;

class ManageUsers extends ManageRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->icon(Heroicon::Plus)
                ->label('Tambah User'),
            ImportAction::make()
                ->label('Import')
                ->icon(Heroicon::ArrowDownTray)
                ->importer(UserImporter::class),
            ExportAction::make()
                ->label('Export')
                ->icon(Heroicon::ArrowUpTray)
                ->exporter(UserExporter::class)
        ];
    }
}
