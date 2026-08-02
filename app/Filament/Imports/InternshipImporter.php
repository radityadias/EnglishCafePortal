<?php

namespace App\Filament\Imports;

use App\Enums\Position;
use App\Models\Internship;
use App\Models\User;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Illuminate\Support\Number;
use Spatie\Permission\Models\Role;

class InternshipImporter extends UserImporter
{
    public static function getOptionsFormComponents(): array
    {
        return [
            Select::make('position')
                ->label('Posisi')
                ->options([Position::Internship->value => 'Karyawan'])
                ->default(Position::Internship->value)
                ->disabled(),

            Select::make('role')
                ->label('Role')
                ->options(fn() => Role::pluck('name', 'name'))
                ->searchable()
                ->required(),
        ];
    }
}
