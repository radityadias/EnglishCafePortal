<?php

namespace App\Filament\Imports;

use App\Enums\Position;
use App\Models\EmployeeProfile;
use App\Models\InternshipProfile;
use App\Models\User;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Section;
use Illuminate\Support\Number;
use Illuminate\Validation\Rules\Enum;
use Spatie\Permission\Models\Role;

class UserImporter extends Importer
{
    protected static ?string $model = User::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('name')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('email')
                ->requiredMapping()
                ->rules(['required', 'email', 'max:255', 'unique:users,email']),
            ImportColumn::make('phone')
                ->rules(['max:255']),
        ];
    }

    public static function getOptionsFormComponents(): array
    {
        return [
            Select::make('position')
                ->label('Posisi')
                ->options([
                    Position::Employee->value => 'Karyawan',
                    Position::Internship->value => 'Internship',
                    Position::Onboarding->value => 'Onboarding',
                    Position::Training->value => 'Training',
                    Position::Nonactive->value => 'Nonaktif',
                ])
                ->searchable()
                ->required(),
            Select::make('role')
                ->label('Role')
                ->options(fn () => Role::pluck('name', 'name'))
                ->searchable()
                ->required(),
        ];
    }

    public function resolveRecord(): User
    {
        return new User();
    }

    protected function afterCreate(): void
    {
        $role = $this->getRoles();

        if ($role) {
            $this->record->assignRole($role);
        }

        $this->handleProfileRecord();
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your user import has completed and ' . Number::format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }

    private function getRoles()
    {
        return $this->options['role'] ?? null;
    }

    private function getPositions(): ?Position
    {
        $value = $this->options['position'] ?? null;

        return $value ? Position::from($value) : null;
    }

    private function isEmployee(?Position $position): bool
    {
        return in_array($position, [
            Position::Employee,
            Position::Training,
            Position::Onboarding
        ], true);
    }

    private function isInternship(?Position $position): bool
    {
        return $position === Position::Internship;
    }

    private function createEmployeeProfileRecord(): void
    {
        EmployeeProfile::firstOrCreate([
            'user_id' => $this->record->id,
        ]);
    }

    private function createInternshipProfileRecord(): void
    {
        InternshipProfile::firstOrCreate([
            'user_id' => $this->record->id,
        ]);
    }

    private function updateUserPosition(?Position $position): void
    {
        $this->record->updateQuietly([
            'position' => $position,
        ]);
    }

    private function handleProfileRecord(): void
    {
        $position = $this->getPositions();

        $this->updateUserPosition($position);

        if ($this->isEmployee($position)) {
            $this->createEmployeeProfileRecord();
        }

        if ($this->isInternship($position)) {
            $this->createInternshipProfileRecord();
        }
    }
}
